/**
 * Format a date string to "5 December, 2025"
 * @param dateString - ISO date string or any valid date input
 * @returns Formatted date string or '—' if invalid
 */
export const formatDate = (dateString: string | null | undefined): string => {
  if (!dateString) return '—';
  
  const date = new Date(dateString);
  // Check if date is valid
  if (isNaN(date.getTime())) return '—';
  
  const day = date.getDate();
  const month = date.toLocaleString('default', { month: 'long' });
  const year = date.getFullYear();
  
  return `${day} ${month}, ${year}`;
};

// You can add other date helpers here
export const formatDateTime = (dateString: string): string => {
  if (!dateString) return '—';
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return '—';
  
  return date.toLocaleString('en-GB', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};