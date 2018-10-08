# safeboda vpc schema
This CloudFormation template, spin up a new VPC with Public and private subnets for SafeBoda in us-east-1 in different AZ. 
I've replicated the setup like at this link but in 2 different AZ
https://docs.aws.amazon.com/vpc/latest/userguide/VPC_Scenario2.html

The resources created are the following.
Private subnets for databases and webservers instances.
Public subnets for bastion host that we use to connect on the db and webserver instances.
Public subnets for the classic load balancer which is used to route call the php script which write to the db.
Access control list for public/private subnet to communicate with each and others.
Security groups for each type of instances that we use.
NAT network with EIP so instances in the private subnet can initiate connection to the internet.
Route table for each subnet in the VPC.

