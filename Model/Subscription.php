<?php
namespace Phppot;

class Subscription
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    public function getMemberSubscription($user_id)
    {
        $query = 'SELECT s.*,o.created FROM tbl_subscription as s LEFT JOIN tbl_orders as o on s.order_id = o.order_id where s.user_id = ?';
        $paramType = 's';
        $paramValue = array(
            $user_id
        );
        $memberSubscription = $this->ds->select($query, $paramType, $paramValue);
        return $memberSubscription;
    }

    /**
     * updateOrder
     *
     * @param string 
     * @return string
     */
    public function updateSubscription($user_id, $status)
    {   
        
        $query = 'UPDATE tbl_subscription set status = ? WHERE user_id = ?';
        $paramType = 'ss';
        $paramValue = array(
            $status,
            $user_id
        );
        $this->ds->execute($query, $paramType, $paramValue);
          
    }

    /**
     * deleteSubscription
     *
     * @param string 
     * @return string
     */
    public function deleteSubscription($user_id)
    {   
        
        $query = 'DELETE FROM tbl_subscription WHERE user_id = ?';
        $paramType = 's';
        $paramValue = array(
            $user_id
        );
        $this->ds->execute($query, $paramType, $paramValue);
          
    }

}


