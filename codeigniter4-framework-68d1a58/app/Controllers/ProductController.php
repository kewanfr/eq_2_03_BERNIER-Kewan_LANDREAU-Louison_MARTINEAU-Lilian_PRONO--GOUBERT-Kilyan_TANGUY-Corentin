<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;

class ProductController extends Controller
{
    public function add() {
        return view('product_form');
    }

    public function addAction() {
        $validationRule = [
            'userfile' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[userfile,1024]', // 1MB max
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
            return view('product_form', $data);
        }

        $img = $this->request->getFile('userfile');

        if (!$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads', $newName);

            $name = $_POST["name"];
            $desc = $_POST["desc"];
            $img_src = "/uploads/" . $newName;
            $price = $_POST["price"];
            $quantity = $_POST["qtt"];

            $model = new ProductModel();

            $model->addProduct($name, $desc, $img_src, $price, $quantity);

            return redirect()->to(base_url('/'));
        }

        $data = ['errors' => 'The file has already been moved.'];
        return view('product_form', $data);
    }

    public function purchase() {
        if (!isset($_GET["id"])) {
            return redirect()->to(base_url('/'));
        }

        $model = new ProductModel();

        $product = $model->getProductById($_GET["id"]);

        return view('product_purchase', $product);
    }
}