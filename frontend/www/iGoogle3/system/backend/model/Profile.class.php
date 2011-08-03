<?php
require_once __DIR__.'/backendautobeans/MUserBean.class.php';
class Profile extends MUserBean
{
	public /*bool*/ function equals(Profile $profile)
	{
		return $this->id				=== $profile->id
			&& $this->socialNetworkID	=== $profile->socialNetworkID
			&& $this->socialNetworkName	=== $profile->socialNetworkName
			&& $this->name				=== $profile->name
			&& $this->firstName			=== $profile->firstName
			&& $this->lastName			=== $profile->lastName
			&& $this->link				=== $profile->link
			&& $this->birthday			=== $profile->birthday
			&& $this->hometown			=== $profile->hometown
			&& $this->gender			=== $profile->gender
			&& $this->email				=== $profile->email
			&& $this->password			=== $profile->password
			&& $this->profilePicture	=== $profile->profilePicture;
	}
	public /*bool*/ function equalsOrNull(Profile $profile)
	{
		return $this->id				=== $profile->id
			&& $this->socialNetworkID	=== $profile->socialNetworkID
			&& $this->socialNetworkName	=== $profile->socialNetworkName
			&& ($this->name				=== $profile->name		||$profile->name	=== null)
			&& ($this->firstName		=== $profile->firstName	||$profile->firstName	=== null)
			&& ($this->lastName			=== $profile->lastName	||$profile->lastName	=== null)
			&& ($this->link				=== $profile->link		||$profile->link	=== null)
			&& ($this->birthday			=== $profile->birthday	||$profile->birthday	=== null)
			&& ($this->hometown			=== $profile->hometown	||$profile->hometown	=== null)
			&& ($this->gender			=== $profile->gender	||$profile->gender	=== null)
			&& ($this->email			=== $profile->email		||$profile->email	=== null)
			&& $this->password			=== $profile->password
			&& ($this->profilePicture	=== $profile->profilePicture	||$profile->profilePicture	=== null);
	}
}
?>
