<?php
namespace Phppot;

class Order
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * updateOrder
     *
     * @param string 
     * @return string
     */
    public function updateOrder($subscription_type, $user_id, $price, $subscriptionId, $transactionId)
    {   
        $currentTime = date('Y-m-d H:i:s');
        $query = 'INSERT INTO tbl_orders (user_id, price, transactionId, created) VALUES (?, ?, ?, ?)';
        $paramType = 'ssss';
        $paramValue = array(
            $user_id,
            $price,
            $transactionId,
            $currentTime
        );
        $orderId = $this->ds->insert($query, $paramType, $paramValue);

        if (!empty($orderId)) {
            $response = '{"OrderStatus": [{"status":"1", "orderID":"'.$orderId.'"}]}';
            $memberSubscription = $this->getMemberSubscription($user_id);
            if(empty($memberSubscription)){
                $query = 'INSERT INTO tbl_subscription (product_id, user_id, order_id, price, status, subscriptionId) VALUES (?, ?, ?, ?, ?, ?)';
                $paramType = 'ssssss';
                $paramValue = array(
                    $subscription_type,
                    $user_id,
                    $orderId,
                    $price,
                    1,
                    $subscriptionId
                );
                $this->ds->insert($query, $paramType, $paramValue);
            }else{
                $query = 'UPDATE tbl_subscription set product_id = ?, order_id = ?, price = ?, status = ?, subscriptionId = ? WHERE user_id = ?';
                $paramType = 'ssssss';
                $paramValue = array(
                    $subscription_type,
                    $orderId,
                    $price,
                    1,
                    $subscriptionId,
                    $user_id
                );
                $this->ds->execute($query, $paramType, $paramValue);
            }  
        }else{
            $response = '{"OrderStatus": [{"status":"0", "orderID":"0"}]}';
        }

        return $response;
    }

    public function getMemberSubscription($user_id)
    {
        $query = 'SELECT * FROM tbl_subscription where user_id = ?';
        $paramType = 's';
        $paramValue = array(
            $user_id
        );
        $memberSubscription = $this->ds->select($query, $paramType, $paramValue);
        return $memberSubscription;
    }
}
