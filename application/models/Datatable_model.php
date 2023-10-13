<?php 

class Datatable_model extends CI_Model
{

    protected $table;
    protected $query;
    protected $column_search;
    protected $column_order;
    protected $order;
    protected $extra;
    public function __construct()
    {
        parent::__construct();
    }
    public function set_init($table, $query, $column_search, $column_order, $order, $extra_where_and_users = false) 
    {
        $this->table = $table;
        $this->query = $query;
        $this->column_search = $column_search;
        $this->column_order = $column_order;
        $this->order = $order;
        $this->extra_where_and_users = $extra_where_and_users;
    }

    public function get_query()
    {
        $sql = ""; 
        
        if (isset($this->query) && is_array($this->query) && !empty($this->query)) {
            $sql = implode(' ', $this->query);
        } else {
            $sql = "SELECT * FROM " . $this->table; 
        }
    
        if($this->extra_where_and_users){
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($_POST['search']['value']) {

                    $sql .= " AND `$item` LIKE '%" . $_POST['search']['value'] . "%'";
                }
                $i++;
            }
        }
        else
        {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($_POST['search']['value']) {
                    if ($i === 0) {
                        $sql .= " WHERE "; 
                    } else {
                        $sql .= " OR ";
                    }
                    $sql .= " `$item` LIKE '%" . $_POST['search']['value'] . "%'";
                }
                $i++;
            }
        }
    
        if (isset($_POST['order'])) {
                $order_column_index = isset($_POST['order']['0']['column']) ? intval($_POST['order']['0']['column']) : 0;
                $order_column_index = ($order_column_index >= 0 && $order_column_index < count($this->column_order)) ? $order_column_index : 0;
                
                $order_column = $this->column_order[$order_column_index];
                $order_direction = $_POST['order']['0']['dir'];
                $sql .= " ORDER BY `$order_column` $order_direction";
            } else if (isset($this->order) && !empty($this->order)) {
                $order = $this->order;
                $order_column = key($order);
                $order_direction = $order[$order_column];
                $sql .= " ORDER BY `$order_column` $order_direction";
            }
            
            return $sql;

        }
    



        public function get_datatables()
        {
                $sql = $this->get_query();
                if ($_POST['length'] != -1) {
                        $start = (int)($_POST['start']) ? (int)$_POST['start'] : 0;
                        $length = (int)($_POST['length']) ? (int)$_POST['length'] : 5;
                        
                        // Append the LIMIT clause to the query string
                        $sql .= " LIMIT $start, $length";
                }
                $query = $this->db->query($sql);

                return $query;

                
        }

        public function count_filtered()
        {
            $sql = $this->get_query();
            $query = $this->db->query($sql);

            return $query;
        }
    
        public function count_all()
        {
            $this->db->from($this->table);
            return $this->db->count_all_results();
        }
}

?>