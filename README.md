# User Registration Form
User Perspective:
1. Created a login form and registration for using PHP with Bootstrap styles.
2. Implemented  validations using JavaScript and jQuery for the registration form, including:
   
i)  First and last name requiring a minimum 3 characters.
ii)  Email address accepting only Gmail accounts.
iii) Phone number validation for a combination of country code and numeric value.
iv)  Passwords requiring a minimum 8 characters with at least 1 capital letter, special character and number.
v)   Profile picture size limit of  2MB.
vi)  All fields made mandatory.

3. Handled validation errors by displaying respective error messages and focusing on the specific field.
4. Implemented form submission and data storage in the MySQL 'user' table, checking for existing email entries to avoid duplicates.
5. Send a confirmation email to the user upon successful registration.

Admin Prospective:
1. Designed an admin login page with credentials (username: admin, password: admin).
2. Developed functionality to display a paginated and sortable Bootstrap table listing all users.
3. Implemented a search filter to enable searching for users by name , email or phone number,
4. Provided options for the admin to add a new user, edit user details(except for email), and delete a user.
