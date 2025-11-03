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
      options: [
        { value: true, label: 'Active' },
        { value: false, label: 'Inactive' }
      ]
    }
  ]
};
