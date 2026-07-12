<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VoucherModel;
use App\Models\PlaceModel; 

class VoucherController extends BaseController
{
    public function index()
    {
        $voucherModel = new \App\Models\VoucherModel();
        $placeModel   = new \App\Models\PlaceModel();
        $uvModel      = new \App\Models\UserVoucherModel();

        $vouchers = $voucherModel->findAll();

        foreach ($vouchers as &$v) {
            $v['terjual'] = $uvModel->where('voucher_id', $v['id'])
                                    ->where('status', 'paid')
                                    ->countAllResults();
            $v['sisa'] = $v['stock'] - $v['terjual'];
        }

        $data['vouchers'] = $vouchers;
        $data['places']   = $placeModel->findAll();
        return view('admin/vouchers/index', $data);
    }

    public function store()
    {
        $model = new VoucherModel();
        $model->save([
            'place_id'       => $this->request->getPost('place_id'),
            'title'          => $this->request->getPost('title'),
            'description'    => $this->request->getPost('description'),
            'price'          => $this->request->getPost('price'),
            'discount_value' => $this->request->getPost('discount_value'),
            'stock'          => $this->request->getPost('stock'),
            'expired_at'     => $this->request->getPost('expired_at'),
        ]);
        return redirect()->to('/admin/vouchers')->with('success', 'Voucher berhasil ditambah!');
    }

    public function update($id)
    {
        $model = new VoucherModel();
        $model->update($id, [
            'title'       => $this->request->getPost('title'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'expired_at'  => $this->request->getPost('expired_at'),
            'place_id'    => $this->request->getPost('place_id'),
            'description' => $this->request->getPost('description'),
        ]);
        return redirect()->to('/admin/vouchers')->with('success', 'Berhasil diperbarui!');
    }

    public function delete($id)
    {
        $voucherModel = new \App\Models\VoucherModel(); 
        
        $voucher = $voucherModel->find($id);
        if ($voucher) {
            $voucherModel->delete($id);
            return redirect()->back()->with('success', 'Voucher berhasil dihapus.');
        }
        
        return redirect()->back()->with('error', 'Voucher tidak ditemukan.');
    }
}