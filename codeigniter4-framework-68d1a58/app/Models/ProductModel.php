<?php

namespace App\Models;

use CodeIgniter\Model;


class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'desc', 'img_src', 'price', 'quantity'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    
    public function getAllProducts()
    {
        return $this->findAll();
    }

    
    public function getProductByName(string $name)
    {
        return $this->where('name', $name)->first();
    }

    
    public function getProductById(int $id)
    {
        return $this->where('id', $id)->first();
    }

    public function getProductsByKeyword(string $keyword)
    {
        return $this->like('desc', $keyword)->findAll();
    }

    
    public function getImageById(int $id)
    {
        $product = $this->select('img_src')->where('id', $id)->first();
        return $product ? $product['img_src'] : null;
    }

    
    public function addProduct(string $name, string $desc, string $img_src, float $price, int $quantity)
    {
        $data = [
            'name' => $name,
            'desc' => $desc,
            'img_src' => $img_src,
            'price' => $price,
            'quantity' => $quantity
        ];

        return $this->insert($data);
    }

    
    public function deleteProduct(int $id)
    {
        return $this->delete($id);
    }

    public function productExists(int $id)
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    public function getProductCount()
    {
        return $this->countAll();
    }
}