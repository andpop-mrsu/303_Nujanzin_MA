<?php
require_once("../entities/Employee.php");
require_once("../entities/Job.php");

class Repository {
    private $dataContext;

    public function __construct(DataContext $dataContext) {
        $this->dataContext = $dataContext;
    }

    public function getAllEmployees() {
        $query = "select personnel_id, first_name, last_name from employees";
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
                    jobs.scheduled_for,
                    jobs.box_number,
                    jobs.id
                from employees, employees_jobs, jobs, service_types 
                where employees.personnel_id=".$employee_id." "
            ."and employees.personnel_id=employees_jobs.employee_id 
                    and employees_jobs.job_id=jobs.id 
                    and jobs.service_id=service_types.id 
                order by employees.last_name, jobs.scheduled_for";

        return $this->dataContext->getAll($query);
    }

    public function addEmployee(Employee $employee) {
        $query = "insert into employees (first_name, last_name, 
                       beginning_of_work, end_of_work) values
                    ('$employee->first_name', 
                     '$employee->last_name',
                     '$employee->beginning_of_work', '$employee->end_of_work')";
        return $this->dataContext->addData($query);
    }

    public function getEmployeeById(int $employee_id) {
        $query = "select * from employees where employees.personnel_id='$employee_id'";

        return $this->dataContext->getAll($query);
    }

    public function updateEmployee(Employee $employee, int $personnelId) {
        $query = "UPDATE employees
                    SET first_name = '$employee->first_name',
                        last_name = '$employee->last_name'
                    WHERE personnel_id = '$personnelId'";

        return $this->dataContext->updateData($query);
    }

    public function updateWorkSchedule(Employee  $employee, int $personnelId) {
        $query = "update employees
                    set beginning_of_work='$employee->beginning_of_work',
                        end_of_work='$employee->end_of_work'
                    where personnel_id='$personnelId'";

        return $this->dataContext->updateData($query);
    }

    public function removeEmployee(int $personnelId) {
        $query = "delete from employees where personnel_id='$personnelId'";

        $this->dataContext->execute($query);
    }

    public function getServiceTypes() {
        $query = "select * from service_types";

        return $this->dataContext->getAll($query);
    }

    public function addJob(Job $job) {
        $dt = $job->schedule_for_date.' '.$job->schedule_for_time;

        $formatDt = strtotime($dt);
        $startDt = date("Y-m-d H:i", strtotime('-30 minutes', $formatDt));
        $endDt = date("Y-m-d H:i", strtotime('+30 minutes', $formatDt));

        $time = strtotime($job->schedule_for_time);
        $startTime = date("H:i", strtotime('+30 minutes', $time));

        $query2 = "select employees.personnel_id from employees where 
                                                         '$startTime' < employees.end_of_work and
                                                         '$job->schedule_for_time' > employees.beginning_of_work limit 1";

        $emps = ($this->dataContext->getAll($query2));

        foreach($emps as $emp) {
            $id = $emp["personnel_id"];
            $query4 = "select jobs.id from jobs, employees_jobs where employees_jobs.employee_id='$id' and jobs.box_number='$job->box_number' and
                                                        employees_jobs.job_id=jobs.id and ('$startDt' <= jobs.scheduled_for and '$endDt' >= jobs.scheduled_for)";

            $res = $this->dataContext->getAll($query4);
            if (count($res) == 0) {
                $findId = $id;
                break;
            }
        }

        if (isset($findId)) {
            $query = "insert into jobs (box_number, scheduled_for, service_id) 
                    values
                    ('$job->box_number', '$dt', '$job->service_id');";
            $this->dataContext->execute($query);

            $query1 = "select id from jobs order by id desc limit 1";
            $jobId = ($this->dataContext->getAll($query1))[0]["id"];


            $query3 = "insert into employees_jobs (job_id, employee_id, percentage_of_revenue) values
                        ('$jobId', '$findId', 1);";
            $this->dataContext->execute($query3);
            return true;
        } else {
            return false;
        }
    }
}
?>