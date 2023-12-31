<?php 

class Recaptcha extends CI_Model
{
	function get_site_key()
	{
		$res = $this->fetch();
		if($res !== false)
		{
			return $res['recaptcha_site'];
		}
		return false;
	}

	function set_site_key($key)
	{
		$res = $this->update('site', $key);
		if($res)
		{
			return true;
		}
		return false;
	}
	
	function get_secret_key()
	{
		$res = $this->fetch();
		if($res !== false)
		{
			return $res['recaptcha_key'];
		}
		return false;
	}

	function set_secret_key($secret_key)
	{
		$res = $this->update('key', $secret_key);
		if($res)
		{
			return true;
		}
		return false;
	}
	
	function is_active()
	{
		$res = $this->fetch();
		if($res !== false)
		{
			if($res['recaptcha_status'] === 'active')
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function get_status()
	{
		$res = $this->fetch();
		if($res !== false)
		{
			return $res['recaptcha_status'];
		}
		return false;
	}

	function set_status(bool $status)
	{
		if($status === true)
		{ 
			$status = 'active';
		}
		else
		{
			$status = 'inactive';
		}
		$res = $this->update('status', $status);
		if($res)
		{
			return true;
		}
		return false;
	}

	function is_valid($token)
	{
		$secret_key = $this->get_secret_key();
        $res = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$token");
        $res = json_decode($res);
        if($res->success){
        	return true;
        }
        return false;
	}

	private function update($field, $value)
	{
		$res = $this->base->update(
			[$field => $value],
			['id' => 'xera_recaptcha'],
			'is_recaptcha',
			'recaptcha_'
		);
		if($res)
		{
			return true;
		}
		return false;
	}

	private function fetch()
	{
		$res = $this->base->fetch(
			'is_recaptcha',
			['id' => 'xera_recaptcha'],
			'recaptcha_'
		);
		if(count($res) > 0)
		{
			return $res[0];
		}
		return false;
	}
}

?>