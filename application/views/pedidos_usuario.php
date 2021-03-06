<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis pedidos | R-Shop</title>
        <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/img/home/simbolo.png"> 
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/prettyPhoto.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/price-range.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/main.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/responsive.css" rel="stylesheet">  
        <link rel="shortcut icon" href="images/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    </head>

    <body>
        <?php $this->load->view('plantilla/encabezado'); ?>

        <section id="cart_items">
            <div class="container">
                <div class="table-responsive cart_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="cart_menu">
                                <td class="image">Nº pedido</td>
                                <td class="description">Nombre</td>
                                <td class="price">Apellidos</td>
                                <td class="quantity">Dirección</td>
                                <td class="total">Fecha</td>
                                <td class="total">Estado</td>
                                <td class="total">Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td class="cart_description">
                                        <p style="text-align: center;"><?= $pedido->numero_pedido ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <p><?= $pedido->nombre ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <p><?= $pedido->apellidos ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <p><?= $pedido->direccion ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <p><?= $pedido->fecha ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <p><?= $pedido->estado ?></p>
                                    </td>
                                    <td class="cart_description">
                                        <?php if ($pedido->estado != 'C' && $pedido->estado != 'ENV') : ?>
                                            <a class="btn btn-danger" href="<?= site_url('Carrito/anular_pedido/' . $pedido->numero_pedido); ?>">Anular</a>
                                        <?php endif; ?>
                                        <a class="btn btn-warning" href="<?= site_url('Carrito/cargar_pdf/' . $pedido->numero_pedido); ?>">PDF</a>
                                        <a class="btn btn-default" href="<?= site_url('Carrito/cargar_detalles/' . $pedido->numero_pedido); ?>">Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section> <!--/#cart_items-->

        <?php $this->load->view('plantilla/pie'); ?>


        <script src="<?= base_url(); ?>assets/js/jquery.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.scrollUp.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.prettyPhoto.js"></script>
        <script src="<?= base_url(); ?>assets/js/main.js"></script>
    </body>
</html>