export const useAuthValidation = () => {
  const validateEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email.trim())
  }

  const cleanPhoneNumber = (phone: string): string => {
    const digits = phone.replace(/\D/g, '')
    let cleaned = digits

    if (cleaned.startsWith('0880') || cleaned.startsWith('0980')) {
      cleaned = cleaned.substring(4)
    } else if (cleaned.startsWith('880')) {
      cleaned = cleaned.substring(3)
    } else if (cleaned.startsWith('80')) {
      cleaned = '0' + cleaned.substring(2)
    }

    if (!cleaned.startsWith('01') && cleaned.length >= 10) {
      if (cleaned.startsWith('1') && cleaned.length === 10) {
        cleaned = '0' + cleaned
      }
    }

    return cleaned
  }

  const validatePhoneNumber = (phone: string): boolean => {
    const cleaned = cleanPhoneNumber(phone)
    const phoneRegex = /^01[3-9]\d{8}$/
    return phoneRegex.test(cleaned) && cleaned.length === 11
  }

  const detectIdentifierType = (identifier: string): { isEmail: boolean; isValid: boolean } => {
    const trimmed = identifier.trim()
    
    if (!trimmed) {
      return { isEmail: true, isValid: true }
    }

    if (validateEmail(trimmed)) {
      return { isEmail: true, isValid: true }
    }

    const isValidPhone = validatePhoneNumber(trimmed)
    return { isEmail: false, isValid: isValidPhone }
  }

  const formatPhoneDisplay = (phone: string): string => {
    const cleaned = cleanPhoneNumber(phone)
    
    if (cleaned.length === 11) {
      return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3')
    }
    
    return phone
  }

  return {
    validateEmail,
    validatePhoneNumber,
    cleanPhoneNumber,
    detectIdentifierType,
    formatPhoneDisplay
  }
}