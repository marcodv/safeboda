# List of playbook to create and provision bastions, dbservers, webserver, loadbalancer and autoscaling group.

## Description
Once that you created the infrastructure with the Vpc.yml you need to create instances in public and private subnet.
Since instance in private subnet are not reachable from internet, unless you go through the bastion, these are the steps to perform to create and provision them.

Steps
1. Use the playbook to spin up generic instances
2. Once that the instances are up we need to provision them
3. Once that the provision is done, you need to generate an AMI for each single instance
4. Finally create the instances in the private subnet

### Hosts file
In this file you need to setup the IP addresses for the bastions, databases and webservers

### Create 2 generic instances
In this phase you need to create 2 generic instances that will be provisioned later as db and webserver.
To build the instance you need to replace the value in the file called **generic_instance_vars.yml** as per description. 
Once that you replaced all the values, execute this command
``` 
$ ansible-playbook create_generic_instance.yml -i hosts
```
When the instances are up and running, in the hosts file put the public ip address of 
- **one of the instances** in the section dbserver
- **one of the instances** in the section webserver

and then you can run the ansible-playbook command to assign to each instance his own role.

``` 
$ ansible-playbook playbook_db.yml -i hosts
$ ansible-playbook playbook_webserver.yml -i hosts
```

Once that the instance are provisioned you need to create an AMI based on these. 
To do that, replace the value of **instance_id** in the file generate_ami.yml and then execute it for the webserver and dbserver

Now that the AMI are generate we can spin up these in the private subnets. The playbooks are using the vars file 
- db_vars.yml
- webserver_vars.yml

Replace the values inside this file with the subnets, ami id, and security group.
Now you can execute the playbooks to make them live.

``` 
$ ansible-playbook create_db.yml -i hosts
$ ansible-playbook create_webserver.yml -i hosts
```

### Create bastions
Now you can create a bastion host which has a configuration file called **bastion_vars.yml**. Replace the value in this file
and then run this playbook

```
$ ansible-playbook create_bastion.yml -i hosts
```

### Create a LoadBalancer
This role, create a Classic LB internet facing.
To spin up this lb , you need to edit the role **roles/elb/tasks/main.yml** and insert the 
- the instances id which will be attached to the ELB
- the subnet for the ELB
- the security group for the ELB

```
$ ansible-playbook playbook_lb.yml 
```

### Create a Launch configuration for the ASG
This role, create a launch configuration used from the ASG
For create this Launch configuration you need to edit the role **roles/lc/tasks/main.yml** and insert the
- the image id of the webservers ami created before
- the ssh key name used to deploy on the server
- the security_groups of the webservers instances

and then you can run this command
```
$ ansible-playbook playbook_lc.yaml
```

### Create an Autoscaling group
This role, create a Autoscaling group where the webserver instances will be scaled up or down
For create this Autoscaling group you need to edit the role **roles/asg/tasks/main.yml** and insert the 
- the load_balancers name created before
- the launch configuration created before
- subnets for the loadbalancer

and then you can run this command
```
$ ansible-playbook playbook_asg.yml
```
