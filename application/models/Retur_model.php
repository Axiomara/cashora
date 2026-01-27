<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_model extends CI_Model {

    public function generate_kode()
    {
        $last = $this->db->order_by('id_retur','DESC')->limit(1)->get('retur')->row();
        $next = $last ? $last->id_retur + 1 : 1;
        return 'RT' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function sudah_diretur($id_transaksi)
    {
        return $this->db
            ->where('id_transaksi', $id_transaksi)
            ->get('retur')
            ->row();
    }

    public function get_transaksi_detail($id_transaksi)
    {
        return $this->db
            ->select('td.*, b.nama_barang, b.kode_barang')
            ->from('transaksi_detail td')
            ->join('barang b','b.id_barang=td.id_barang')
            ->where('td.id_transaksi', $id_transaksi)
            ->get()
            ->result();
    }

    public function create_retur($id_transaksi, $items)
    {
        $this->db->trans_begin();

        $kode = $this->generate_kode();
        $total = 0;

        $this->db->insert('retur', [
            'kode_retur' => $kode,
            'id_transaksi' => $id_transaksi,
            'tanggal' => date('Y-m-d H:i:s'),
            'total_retur' => 0
        ]);

        $id_retur = $this->db->insert_id();

        foreach ($items as $id_barang => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $detail = $this->db
                ->where('id_transaksi', $id_transaksi)
                ->where('id_barang', $id_barang)
                ->get('transaksi_detail')
                ->row();

            if (!$detail || $qty > $detail->qty) {
                $this->db->trans_rollback();
                return ['status'=>false,'message'=>'Qty retur tidak valid'];
            }

            $subtotal = $qty * $detail->harga;
            $total += $subtotal;

            $this->db->insert('retur_detail',[
                'id_retur'=>$id_retur,
                'id_barang'=>$id_barang,
                'qty'=>$qty,
                'harga'=>$detail->harga,
                'subtotal'=>$subtotal
            ]);

            // kembalikan stok
            $this->db->set('stok','stok+'.$qty,false)
                ->where('id_barang',$id_barang)
                ->update('barang');
        }

        $this->db->where('id_retur',$id_retur)
            ->update('retur',['total_retur'=>$total]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status'=>false,'message'=>'Gagal simpan retur'];
        }

        $this->db->trans_commit();
        return ['status'=>true,'id_retur'=>$id_retur];
    }

    public function get_retur($id_retur)
    {
        return $this->db->where('id_retur',$id_retur)->get('retur')->row();
    }

    public function get_retur_detail($id_retur)
    {
        return $this->db
            ->select('rd.*, b.nama_barang, b.kode_barang')
            ->from('retur_detail rd')
            ->join('barang b','b.id_barang=rd.id_barang')
            ->where('rd.id_retur',$id_retur)
            ->get()
            ->result();
    }
}
