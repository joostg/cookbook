<?php

class Recipe extends Base
{
	public function getRecipes()
	{
		/*$user = "joost";
		$hash = password_hash("", PASSWORD_DEFAULT);

		$sql = "INSERT INTO users (user, hash) VALUES ('{$user}', '{$hash}')";
		$stmt = $this->db->prepare($sql);
		$status = $stmt->execute();*/

		return array('data' => 'sa');
	}
}
