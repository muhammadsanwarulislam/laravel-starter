export const userFormConfig = {
  title: 'User Information',
  fields: [
    {
      key: 'name',
      label: 'Name',
      type: 'text',
      required: true,
      translatable: true
    },
    {
      key: 'email',
      label: 'Email',
      type: 'email',
      required: true
    },
    {
      key: 'phone',
      label: 'Phone',
      type: 'tel',
      required: true
    },
    {
      key: 'password',
      label: 'Password',
      type: 'password',
      required: false,
      showOnEdit: false, 
      hint: 'Leave blank to keep current password'
    },
    {
      key: 'status',
      label: 'Status',
      type: 'select',
      required: false,
      defaultOption: 'Select Status',
      options: [
        { value: true, label: 'Active' },
        { value: false, label: 'Inactive' }
      ]
    }
  ]
};

export const languageFormConfig = {
  title: 'Language Settings',
  fields: [
    {
      key: 'name',
      label: 'Language Name',
      type: 'text',
      required: true,
      translatable: true
    },
    {
      key: 'code',
      label: 'Language Code',
      type: 'text',
      required: true
    },
    {
      key: 'locale',
      label: 'Locale',
      type: 'text',
      required: true
    },
    {
      key: 'is_default',
      label: 'Default Language',
      type: 'checkbox',
      required: false
    },
    {
      key: 'status',
      label: 'Status',
      type: 'select',
      required: false,
      defaultOption: 'Select Status',
      options: [
        { value: true, label: 'Active' },
        { value: false, label: 'Inactive' }
      ]
    }
  ]
};
