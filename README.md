# Human Resource Paperwork Project (Backend Repository)
The project consists of an iOS application and a React dashboard which are connected via a Laravel API

## The applications features include (but are not limited to):
* Roles and permissions 
* Attendance using gps and local authentication (ex. Face id, fingerprint…) at specific employee shift
* Auto generated Salary slip at the start of the month with automated deductions 
* Scheduled and unscheduled vacations 
* Mission request which represents any type of work that an employee does outside the company’s premises to track the their expenses.
* Task assigning as a communication unit in the application 
* Work from home request
* Leave requests  
* Customize the company configuration ( info , location , full /half day deduction..)
* Weekends and holidays 
* Add deductions/earnings/monthly taxes to salary
* Approve and deny requests with schedulers to change the request status
* Add mission expenses to employee salary after reviewed by the accountant 
* Overtime tracking 
* Alert and notifications 
* Logging requests and attendance
* Holidays and weekends
* Shifts


### Schedulers commands
> To Generate Daily attendance 
```
Php artisan generate:attendance  
```
> To Deduct Absence from the salary slip of the month 
```
Php artisan deduct:absence
```
> To take absence
```
Php artisan absent:employee
```
> To Generate Salary Slip at the start of the month 
```
Php artisan generate:slips  
```
> To Change the request status
```
Php artisan statuschange
Php artisan makerequestcanceled
Php artisan finishrequest
```
