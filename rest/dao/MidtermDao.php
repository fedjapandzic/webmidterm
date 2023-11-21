<?php
require_once __DIR__ . '/../config.php'; 
class MidtermDao {

    private $conn;

    /**
    * constructor of dao class
    */
    public function __construct(){
        try {
          

        /** TODO
        * List parameters such as servername, username, password, schema. Make sure to use appropriate port
        */
        $host = Config::$host;
        $username = Config::$username;
        $password = Config::$password;
        $schema = Config::$database;
        $port = Config::$port;

        /** TODO
        * Create new connection
        * Use $options array as last parameter to new PDO call after the password
        */
        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$schema", $username, $password);

        // set the PDO error mode to exception
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
    * Implement DAO method used to get cap table
    */
    public function cap_table(){
      $query = "SELECT * FROM cap_table";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    }
    public function getShareClass($share_class_id)
  {
    $query = "SELECT * FROM share_classes WHERE id = :share_class_id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['share_class_id' => $share_class_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
  }
  public function getShareClassCategory($share_class_category_id)
  {
    $query = "SELECT * FROM share_class_categories WHERE id = :share_class_category_id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['share_class_category_id' => $share_class_category_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public function getInvestor($investor_id)
  {
    $query = "SELECT * FROM investors WHERE id = :investor_id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['investor_id' => $investor_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

    /** TODO
    * Implement DAO method used to add cap table record
    */
    public function add_cap_table_record($entity){
      $columns = implode(", ", array_keys($entity));
      $values = implode(", :", array_keys($entity));
      $placeholders = implode(", ", array_fill(0, count($entity), "?"));
      
      $query = "INSERT INTO cap_table" . "(".$columns.") VALUeS"."(".$placeholders.")";
      $stmt = $this->conn->prepare($query);
        $stmt->execute(array_values($entity));
      
        $entity['id'] = $this->conn->lastInsertId();
        return $entity;


    }

    /** TODO
    * Implement DAO method to return list of categories with total shares amount
    */
    public function categories(){
      $query = "SELECT c.description, SUM(ct.diluted_shares) AS total_shares
    FROM share_class_categories c
    JOIN cap_table ct ON c.id = ct.share_class_category_id
    GROUP BY c.id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    }

    /** TODO
    * Implement DAO method to delete investor
    */
    public function delete_investor($id){
      $stmt = $this->conn->prepare("DELETE FROM investors WHERE id=:id");
    $stmt->bindParam(':id', $id); // SQL injection prevention
    $stmt->execute();

    }
}
?>
