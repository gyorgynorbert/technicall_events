# Needed Changes

## 1. Add Photo Upload Ability for Students

### Current State
- Students exist in the system with basic information
- No photo upload functionality currently available

### Requirements
- Allow uploading photos for individual students
- Store photos securely (consider storage location: local filesystem vs cloud storage)
- Display student photos in relevant views (student list, student detail page)
- Handle photo validation:
  - File type restrictions (jpg, jpeg, png)
  - File size limits (e.g., max 5MB)
  - Image dimensions validation if needed
- Provide ability to update/replace existing photos
- Consider whether to allow multiple photos per student or single profile photo

### Implementation Areas
- `app/Http/Controllers/Admin/StudentController.php` - Add upload logic
- `app/Models/Student.php` - Add photo field/relationship
- Student views - Add upload form UI
- Database migration - Add photo storage field
- Storage configuration - Set up proper storage disk
- Form validation rules

---

## 2. Checkout Form Validation

### Current State
- Order checkout process exists at `app/Http/Controllers/OrderController.php`
- Current validation state needs review

### Requirements
- Email validation:
  - Proper format validation
  - Check for valid email syntax
  - Consider email uniqueness if creating user accounts
- Additional field validations needed:
  - Name fields (required, min/max length)
  - Phone number (format validation)
  - Address fields (if applicable)
  - Payment information validation
- Frontend validation:
  - Real-time validation feedback
  - Clear error messages
  - Prevent form submission with invalid data
- Backend validation:
  - Server-side validation rules in OrderController
  - Custom validation messages
  - Handle validation errors gracefully

### Implementation Areas
- `app/Http/Controllers/OrderController.php` - Add/enhance validation rules
- Order views (checkout form) - Add frontend validation
- Consider Form Request class for complex validation logic
- Error message translations if needed
- JavaScript validation for better UX

### Validation Rules to Implement
```php
'email' => 'required|email:rfc,dns',
'name' => 'required|string|min:2|max:255',
'phone' => 'required|regex:/^[0-9\-\+\(\)\s]+$/',
// Add other fields as needed
```

---

## Priority
1. Checkout form validation (critical for order integrity)
2. Student photo upload (enhancement feature)

## Notes
- Both features should include proper error handling
- Consider user experience and provide clear feedback
- Test thoroughly before deployment
- Update documentation after implementation
