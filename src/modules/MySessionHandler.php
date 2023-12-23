<?php
class MySessionHandler implements SessionHandlerInterface{
  private $db; // database connection object

  public function __construct ($db) {
    $this->db = $db; // assign the database connection
    // register the session handler functions
    session_set_save_handler (
      array ($this, 'open'),
      array ($this, 'close'),
      array ($this, 'read'),
      array ($this, 'write'),
      array ($this, 'destroy'),
      array ($this, 'gc')
    );
    session_start();
  }

  public function open ($db, $sessionName) {
    // nothing to do, connection is already open
    return true;
  }

  public function close () {
    // nothing to do, connection is already closed
    return true;
  }

  public function read ($id) {
    // escape the session ID
    $id = $this->db->real_escape_string ($id);
    // query the session table for the session data
    $sql = "SELECT data FROM sessions WHERE id = '$id'";
    $result = $this->db->query ($sql);
    // if a row is found, return the data, otherwise return an empty string
    if ($result && $result->num_rows == 1) {
      $row = $result->fetch_assoc ();
      return $row ['data'];
    } else {
      return '';
    }
  }

  public function write ($id, $data) {
    // escape the session ID and data
    $id = $this->db->real_escape_string ($id);
    $data = $this->db->real_escape_string ($data);
    // update or insert the session data in the session table
    $sql = "REPLACE INTO sessions (id, data) VALUES ('$id', '$data')";
    $result = $this->db->query ($sql);
    // return true if successful, false otherwise
    return $result;
  }

  public function destroy ($id) {
    // escape the session ID
    $id = $this->db->real_escape_string ($id);
    // delete the session data from the session table
    $sql = "DELETE FROM sessions WHERE id = '$id'";
    $result = $this->db->query ($sql);
    // return true if successful, false otherwise
    return $result;
  }

  public function gc ($maxlifetime) {
    // delete the expired session data from the session table
    $sql = "DELETE FROM sessions WHERE UNIX_TIMESTAMP() - data > $maxlifetime";
    $result = $this->db->query ($sql);
    // return true if successful, false otherwise
    return $result;
  }
}
?>