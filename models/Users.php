<?php


class Users {
    private $db;
    private $table = 'profiles';

    public function __construct($db){
        $this->db = $db;
    }

    public function getAllUsers(){
        $query = "SELECT * FROM profiles";
        $result = $this->db->getConnection()->query($query);

        if($result->num_rows > 0){
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function getUserById($id){
        $query = "SELECT * FROM profiles WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function updateUser($id,$name,$description,$status){ 
        $query = "UPDATE profiles SET name = ?, description = ?, status = ? WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("sssi",$name,$description,$status,$id);
        return $stmt->execute();
    }

    public function createUser ($name, $description, $status) {
        $query = "INSERT INTO profiles (name, description, status) VALUES (?,?,?)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("sss",$name,$description,$status);
        return $stmt->execute(); 
    }

    public function deleteUser($id){
        $query = "DELETE FROM profiles WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }
    
}
