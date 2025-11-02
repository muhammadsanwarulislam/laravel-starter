export interface CrudState<T> {
  data: T[];
  isLoading: boolean;
  isSuccess: boolean;
  pagination: any;
}

export interface CrudOptions {
  endpoint: string;
  defaultItemsPerPage?: number;
}

export function useCrudOperations<T extends { id: number | string }>(options: CrudOptions) {
  const { endpoint, defaultItemsPerPage = 10 } = options;

  const state = reactive<CrudState<T>>({
    data: [],
    isLoading: false,
    isSuccess: false,
    pagination: null
  });

  const searchQuery = ref('');
  const currentPage = ref(1);
  const itemsPerPage = ref(defaultItemsPerPage);
  const filters = ref<Record<string, string>>({});

  const buildApiParams = () => {
    const params: any = {
      limit: itemsPerPage.value,
      offset: (currentPage.value - 1) * itemsPerPage.value,
      option: 'list'
    };

    // Handle search
    if (searchQuery.value.trim() !== '') {
      params.option = 'search';
      params.searchData = searchQuery.value;
      params.searchFields = 'name,email,phone';
    }
    // Handle filters
    else if (Object.keys(filters.value).length > 0) {
      params.option = 'search';
      const activeFilters = Object.entries(filters.value)
        .filter(([_, value]) => value !== 'all' && value !== '')
        .reduce((acc, [key, value]) => {
          acc.keys.push(key);
          acc.values.push(value);
          return acc;
        }, { keys: [] as string[], values: [] as string[] });

      if (activeFilters.keys.length > 0) {
        params.searchFields = activeFilters.keys.join(',');
        params.searchData = activeFilters.values.join(',');
      }
    }

    return params;
  };

  const loadData = async (customParams?: any) => {
    state.isLoading = true;
    
    try {
      const params = customParams || buildApiParams();
      const queryString = new URLSearchParams();
      
      Object.keys(params).forEach(key => {
        if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
          queryString.append(key, params[key]);
        }
      });

      const url = `${endpoint}?${queryString.toString()}`;
      const response: any = await $http(url, { method: 'GET' });
      
      if (response?.data) {
        state.data = response.data.data || response.data;
        state.pagination = response.data.pagination || {};
        if (state.pagination.current_page) {
          currentPage.value = state.pagination.current_page;
        }
      } else {
        state.data = response;
      }
      state.isSuccess = true;
    } catch (error) {
      state.isSuccess = false;
      console.error(`Error loading ${endpoint}:`, error);
      throw error;
    } finally {
      state.isLoading = false;
    }
  };

const createItem = async (formData: any) => {
  try {
    const apiData = {
      ...formData,
      ...(formData.translations ? transformTranslations(formData.translations) : {})
    };
    
    await $http(endpoint, {
      method: 'POST',
      body: apiData
    });
    await loadData();
  } catch (error) {
    console.error(`Error creating ${endpoint}:`, error);
    throw error;
  }
};

const updateItem = async (id: number | string, formData: any) => {
  try {
    const apiData = {
      ...formData,
      ...(formData.translations ? transformTranslations(formData.translations) : {})
    };
    
    await $http(`${endpoint}/${id}`, {
      method: 'PUT',
      body: apiData
    });
    await loadData();
  } catch (error) {
    console.error(`Error updating ${endpoint}:`, error);
    throw error;
  }
};

// Helper function to transform translations
const transformTranslations = (translations: Record<string, Record<string, string>>) => {
  const result: any = {};
  
  Object.keys(translations).forEach(field => {
    const fieldTranslations = translations[field] || {};
    Object.keys(fieldTranslations).forEach(lang => {
      const value = fieldTranslations[lang];
      if (value !== undefined && value !== null) {
        result[`${field}_${lang}`] = value;
      }
    });
  });
  
  return result;
};

  const deleteItem = async (id: number | string) => {
    try {
      await $http(`${endpoint}/${id}`, {
        method: 'DELETE'
      });
      await loadData();
    } catch (error) {
      console.error(`Error deleting ${endpoint}:`, error);
      throw error;
    }
  };

  const handleSearch = (query: string) => {
    searchQuery.value = query;
    currentPage.value = 1;
    loadData();
  };

  const handleFilterChange = (key: string, value: string) => {
    filters.value[key] = value;
    currentPage.value = 1;
    loadData();
  };

  const handleItemsPerPageChange = (items: number) => {
    itemsPerPage.value = items;
    currentPage.value = 1;
    loadData();
  };

  const nextPage = () => {
    if (state.pagination?.next_page_url) {
      currentPage.value++;
      loadData();
    }
  };

  const prevPage = () => {
    if (state.pagination?.prev_page_url) {
      currentPage.value--;
      loadData();
    }
  };

  const goToPage = (page: number) => {
    currentPage.value = page;
    loadData();
  };

  return {
    // State
    ...toRefs(state),
    searchQuery,
    currentPage,
    itemsPerPage,
    filters,

    // Methods
    loadData,
    createItem,
    updateItem,
    deleteItem,
    handleSearch,
    handleFilterChange,
    handleItemsPerPageChange,
    nextPage,
    prevPage,
    goToPage,
    buildApiParams
  };
}