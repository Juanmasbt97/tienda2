<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carrito extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('cart');
        $this->load->helper('form');
        $this->load->model(array('Carrito_model', 'Pdf_model', 'Login_model'));
    }

    public function index() {
        $this->load->view('carrito');
    }

    /**
     * Añade al carrito el producto seleccionado
     * @param int $id
     */
    public function agregar_producto($id) {
        $producto = $this->Carrito_model->producto_selc($id);
        $cantidad = $this->input->post('cant');

        $carrito = $this->cart->contents(); //Obtenemos el contenido del carrito

        foreach ($carrito as $item) {
            //Si el id del producto es igual al de uno que ya tengamos en el carrito
            //le sumamos la cantidad introducida a la cantidad que tenía anteriormente
            if ($item['id'] == $id) {
                $item['qty'] += $cantidad;
            }
        }

        if ($producto->stock >= $cantidad) {
            //Recogemos los productos en un array para insertarlos en el carrito
            $data = array(
                'id' => $id,
                'qty' => $cantidad,
                'price' => $producto->precio_venta,
                'name' => $producto->nombre,
                'img' => $producto->imagen,
                'cod' => $producto->codigo,
                'stock' => $producto->stock
            );

            $this->cart->insert($data);

            $this->db->set('stock', $producto->stock - $cantidad);
            $this->db->where('id_producto', $id);
            $this->db->update('producto');
            redirect('Carrito');
        } else {
            redirect('Detalles_producto/get_zapatilla/' . $producto->id_producto);
        }
    }

    /**
     * Elimina del carrito el producto seleccionado
     * @param string $rowid
     */
    public function eliminar_producto($rowid) {
        //Para eliminar un producto concreto conseguimos su rowid 
        //y ponemos qty (la cantidad) a 0
        $producto = array(
            'rowid' => $rowid,
            'qty' => 0
        );

        //Actualizamos el carrito pasando el array del producto eliminado
        $this->cart->update($producto);
        redirect('Carrito');
    }

    /**
     * Actualiza el carrito en el caso de que se hayan modificado las cantidades 
     * de los productos del mismo
     */
    public function actualizar_carrito() {
        foreach ($this->cart->contents() as $item) {
            $item['qty'] = $this->input->post($item['rowid']);
        }
        $this->cart->update($item);
        redirect('Carrito');
    }

    /**
     * Destruye el carrito
     */
    public function eliminar_carrito() {
        $this->cart->destroy();
        redirect('Carrito');
    }

    /**
     * Llama a una función del modelo, la cual se encarga de insertar el pedido
     */
    public function agregar_pedido() {
        $this->Carrito_model->inserta_pedido();
        $id_pedido = $this->Carrito_model->get_pedido();
        $this->genera_pdf($id_pedido);
        $this->eliminar_carrito();
    }

    public function genera_pdf($id_pedido) {
        $pdf = new Pagina_PDF();
        $pdf->AddPage();
        $usuario = $this->Login_model->get_usuario();
        $pdf->datos_pedido($usuario);
        $header = array('Id producto', 'Cantidad', 'Subtotal');
        $data = $this->Carrito_model->get_lineas_pedido($id_pedido);
        $pdf->Albaran($header, $data);
        $pdf->Output('F');

        $this->email->from('segundodaw2019@gmail.com');
        $this->email->to($this->session->userdata('email'));
        $this->email->subject('Detalle del pedido realizado');
        $this->email->attach('doc.pdf', 'inline', 'pedido.pdf');
        $this->email->message($this->load->view('resumen_tabla', '', TRUE));
        $this->email->send();
    }

    public function cargar_resumen() {
        $this->load->view('resumen_pedido');
    }

    public function usuarios_pedido() {
        $data['pedidos'] = $this->Carrito_model->get_pedidos_usuario();
        $this->load->view('pedidos_usuario', $data);
    }

    public function cargar_pdf($numeropedido) {
        $pdf = new Pagina_PDF();
        $pdf->AddPage();
        $usuario = $this->Login_model->get_usuario();
        $pdf->datos_pedido($usuario);
        $header = array('Id producto', 'Cantidad', 'Subtotal');
        $data = $this->Carrito_model->get_lineas_pedido($numeropedido);
        $pdf->Albaran($header, $data);
        $pdf->Output('I');
    }

    public function anular_pedido($numeropedido) {
        $this->Carrito_model->actualizar_estado($numeropedido);
        $data['pedidos'] = $this->Carrito_model->get_pedidos_usuario();
        $this->load->view('pedidos_usuario', $data);
    }
    
    public function cargar_detalles($numeropedido) {
        $this->Carrito_model->actualizar_estado($numeropedido);
        $data['lineas'] = $this->Carrito_model->get_lineas_pedido($numeropedido);
        $this->load->view('detalles_pedido', $data);
    }

}
