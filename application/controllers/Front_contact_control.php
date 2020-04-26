<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер страницы "Контакты"
 *
 * Методы для работы со страницей "Контакты" в пользоватьской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_contact_control extends Front_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_contact_model');
    }

    /**
     * Загрузить шаблон страницы
     *
     * @return void
     */
    function index()
    {
        $data = $this->front_contact_model->get_contact_page();
        $this->_viewer('front/contact_view', $data);
    }

    /**
     * Отправить сообщение
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, валидирует, отправит сообщение
     * и выведет json ответ.
     *
     * @return void
     */
    function send_mail()
    {
        /**
         * валидация, подготовка данных
         */
        !$this->input->post() ? exit('error') : null;
        $this->input->post('fuck_bot') !== '' ? exit('bot') : null;
        $p = array_map('trim', $this->input->post());
        !filter_var($p['mail'], FILTER_VALIDATE_EMAIL) ? exit('nomail') : null;
        $domen = str_replace('www.', '', $this->input->server('HTTP_HOST'));
        $msg = '
<html>
    <head>
        <title>Сообщение с ' . $domen . '</title>
    </head>
    <body>
        <h2>Сообщение с ' . $domen . '</h2>
        Дата и время отправки: ' . date('d.m.Y H:i:s') . '<br>
        Email отправителя: ' . $p['mail'] . '<br>
        Имя отправителя: ' . strip_tags($p['name']) . '<br>
        Текст сообщения: ' . strip_tags($p['text']) . '<br>
    </body>
</html>
';
        /**
         * отправка сообщения
         */
        $this->load->library('email');
        $this->email->from('Robot@' . $domen, $this->app('conf.site_name'));
        $this->email->to($this->app('conf.site_mail'));
        $this->email->subject('Сообщение с ' . $domen);
        $this->email->message($msg);
        $this->email->send() ? exit('ok') : exit('error');
    }
}
