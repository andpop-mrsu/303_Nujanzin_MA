<?php
    class Repository {
        private $dataContext;

        public function __construct(DataContext $dataContext) {
            $this->dataContext = $dataContext;
        }

        public function getAllEmployees() {
            $query = "select employees.personnel_id, employees.first_name, employees.last_name from employees";
            return $this->dataContext->getAll($query);
        }

        public function getAllEmployeesJobs() {
            $query = "select employees.personnel_id, 
                    employees.first_name, 
                    employees.last_name, 
                    service_types.name, 
                    datetime(jobs.scheduled_for, 'unixepoch') as scheduled_for
                from employees, employees_jobs, jobs, service_types 
                where employees.personnel_id=employees_jobs.employee_id 
                    and employees_jobs.job_id=jobs.id 
                    and jobs.service_id=service_types.id 
                order by employees.last_name, jobs.scheduled_for";

            return $this->dataContext->getAll($query);
        }

        public function getAllEmployeeJobsById(int $employee_id) {
            $query = "select employees.personnel_id,
                    employees.first_name, 
                    employees.last_name, 
                    service_types.name, 
                    datetime(jobs.scheduled_for, 'unixepoch') as scheduled_for
                from employees, employees_jobs, jobs, service_types 
                where employees.personnel_id=".$employee_id." "
                    ."and employees.personnel_id=employees_jobs.employee_id 
                    and employees_jobs.job_id=jobs.id 
                    and jobs.service_id=service_types.id 
                order by employees.last_name, jobs.scheduled_for";

            return $this->dataContext->getAll($query);
        }
    }
?>
