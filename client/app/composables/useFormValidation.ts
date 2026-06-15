import { reactive, ref, unref, type UnwrapRef } from 'vue'

type ValidationRule<T> = (value: any, formValues: T) => string | true
type ValidationSchema<T> = {
  [K in keyof T]?: ValidationRule<T> | Array<ValidationRule<T>>
}

interface UseFormOptions<T extends Record<string, any>> {
  initialValues: T
  validationSchema?: ValidationSchema<T>
  onSubmit: (values: T) => Promise<any>
}

export function useFormValidation<T extends Record<string, any>>(options: UseFormOptions<T>) {
  const values = reactive<T>({ ...options.initialValues }) as UnwrapRef<T>
  const errors = reactive<Record<keyof T, string>>({} as any)
  const isSubmitting = ref(false)


  const setFieldError = (field: keyof T, message: string) => {
    errors[field] = message
  }

  
  const setApiErrors = (apiErrors: Record<string, string[]>) => {
    // Clear previous errors first
    Object.keys(errors).forEach(key => {
      errors[key as keyof T] = ''
    })
    for (const [field, messages] of Object.entries(apiErrors)) {
      if (field in values) {
        errors[field as keyof T] = messages[0] || 'Invalid value'
      }
    }
  }


  const validate = (): boolean => {
    if (!options.validationSchema) return true
    let isValid = true

    Object.keys(errors).forEach(key => {
      errors[key as keyof T] = ''
    })

    for (const [field, rules] of Object.entries(options.validationSchema)) {
      const value = values[field as keyof T]
      const ruleList = Array.isArray(rules) ? rules : [rules]
      for (const rule of ruleList) {
        const result = rule(value, values)
        if (result !== true) {
          errors[field as keyof T] = result
          isValid = false
          break
        }
      }
    }
    return isValid
  }


  const handleSubmit = async () => {
    if (!validate()) return

    isSubmitting.value = true
    try {
      const response = await options.onSubmit({ ...values })
      if (response && !response.success && response.errors) {
        setApiErrors(response.errors)
        return
      }

      return response
    } catch (error: any) {
      if (error?.errors) {
        setApiErrors(error.errors)
      }
      throw error
    } finally {
      isSubmitting.value = false
    }
  }


  const resetForm = () => {
    Object.assign(values, options.initialValues)
    Object.keys(errors).forEach(key => {
      errors[key as keyof T] = ''
    })
  }


  const setValues = (newValues: Partial<T>) => {
    Object.assign(values, newValues)
  }

  return {
    values,
    errors,
    isSubmitting,
    setFieldError,
    setApiErrors,
    handleSubmit,
    resetForm,
    setValues,
    validate, 
  }
}