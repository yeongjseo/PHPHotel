<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Board extends MY_Controller {
	function __construct() {
		parent::__construct();
	
		$this->load->model('board_model');

		$this->load->library('paging');
	}
	
	function list() {
		$type = $this->input->get('type');
		if ($type == null)
			redirect('/errors/access');
		
		$this->paging->init($this->input);
		
		$args = array('type'=>$type, 'paging'=>$this->paging);
		
		$this->paging->row_count = $this->board_model->count_board($args);
		$list = $this->board_model->list_board($args);
		
		$this->paging->calculate();
		
		$type_long_desc = $this->board_model->get_type_long_desc($type);
		$args = array('type'=>$type, 'type_long_desc'=>$type_long_desc, 'paging'=>$this->paging, 'list'=>$list);
		$this->_load_view('/board/board_list', $args);
	}
	
	function detail() {
		$type = $this->input->get_post('type');
		if ($type == null)
			redirect('/errors/access');
		$board_id = $this->input->get_post('id');
		$type_long_desc = $this->board_model->get_type_long_desc($type);
		$this->paging->init($this->input);
		
		$board = $this->board_model->get_board(array('type'=>$type, 'id'=>$board_id));
		
		if (! $board) {
			$this->session->set_flashdata('message', array('result'=>'error', 'reason'=>'게시글이 삭제되었습니다'));
			redirect("/board/list?type={$type}&{$this->paging->make_query()}");
			return;
		}
		
		$files = $this->board_model->list_board_file(array('board_id'=>$board->ID));
		
		if ($this->_isget()) {
			$this->board_model->increase_board_read_count(array('type'=>$type, 'id'=>$board_id));
			
			$args = array('type'=>$type, 'type_long_desc'=>$type_long_desc, 'paging'=>$this->paging, 
							'board'=>$board, 'files'=>$files);
			$this->_load_view('/board/board_detail', $args);
			return;
		}
		
		$board->TITLE = $this->input->post('title');
		$board->CONTENT = $this->input->post('content');
		
		$this->board_model->update_board(array('board'=>$board));

		
		$upload_files = $this->_upload_board_file();
		$deleted_file_ids = array();
		
		foreach ($files as $i=>$file) {
			$file_id = "file_id_{$file->ID}";
			
			if (! $this->input->post($file_id))
				array_push($deleted_file_ids, $file->ID);
		}
		
		foreach ($deleted_file_ids as $file_id) {
			$this->board_model->delete_board_file(array('id'=>$file_id));
		}

		foreach ($upload_files as $name=>$file) {
			$args = array('board_id'=>$board_id, 'filename'=>$file['filename'],
					'saved_filename'=>$file['saved_filename'], 'filesize'=>$file['filesize']);
		
			$this->board_model->insert_board_file($args);
		}
		
		
		redirect("/board/list?type={$type}&{$this->paging->make_query()}");
	}
	
	
	function insert() {
		$this->_require_login();
		
		$type = $this->input->get('type');
		if ($type == null)
			redirect('/errors/access');
		
		$this->paging->init($this->input);
		$type_long_desc = $this->board_model->get_type_long_desc($type);
		$args = array('type'=>$type, 'type_long_desc'=>$type_long_desc, 'paging'=>$this->paging);
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', '제목', 'required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('content', '내용', 'required|min_length[2]');
		
		if ($this->form_validation->run() === false) {
			if (($error = $this->_get_form_error($_POST)) != null)
				$this->session->set_flashdata('message', array('result'=>'error', 'reason'=>$error));
				
				
			$this->_load_view('/board/board_insert', $args);
			return;
		}
		
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		log_message('debug', "title={$title}, content={$content}");			
		
		$files = $this->_upload_board_file();
		if (! $files) {
			$this->session->set_flashdata('message', array('result'=>'error', 
											'reason'=>$this->upload->display_errors('', '')));
			
			$this->_load_view('/board/board_insert', $args);
			return;
		}
		
		$user = $this->session->userdata('user');
		
		/*
		$args = array('type'=>$type, 'user_id'=>$user->ID, 'title'=>$title, 'content'=>$content);
		$board_id = $this->board_model->insert_board($args);
		*/
		
		$board_id = $this->board_model->get_board_nextval();
		$args = array('id'=>$board_id, 'type'=>$type, 'user_id'=>$user->ID, 'title'=>$title, 'content'=>$content);
		$this->board_model->insert_board_by_nextval($args);

		foreach ($files as $name=>$file) {
			$args = array('board_id'=>$board_id, 'filename'=>$file['filename'], 
							'saved_filename'=>$file['saved_filename'], 'filesize'=>$file['filesize']);

			$this->board_model->insert_board_file($args);
		}
		
		redirect('/board/list?type='.$type);
	}
	
	function delete() {
		$type = $this->input->post('type');
		$id = $this->input->post('id');
	
		$this->paging->init($this->input);
		
		$this->board_model->delete_board(array('id'=>$id));
		
		redirect("/board/list?type={$type}&{$this->paging->make_query()}");
	}
	
	function file_download() {
		$type = $this->input->post('type');
		$id = $this->input->post('file_id');
		
		$file = $this->board_model->get_board_file(array('id'=>$id));
		
		$this->load->helper('download');

		force_download("./static/user/{$file->SAVED_FILENAME}", NULL);
		
	}
	
	function reply_list() {
		$board_id = $this->input->get('board_id');
		$this->paging->init($this->input);
		
		log_message('debug', "board_id={$board_id}, page_num={$this->paging->page_num}");
		
		$args = array('board_id'=>$board_id, 'paging'=>$this->paging);
		
		$this->paging->row_count = $this->board_model->count_board_reply($args);
		$list = $this->board_model->list_board_reply($args);
		
		$this->paging->calculate();

		header("Content-Type: application/json", true);
		echo json_encode(array('replyCount'=>$this->paging->row_count, 'list'=>$list));
		
	}
	
	function reply_insert() {
		header("Content-Type: application/json", true);
		try {
			/*
			// By Form
			$data = json_decode($_POST['data'], true);
			log_message('debug', "board_id={$data['board_id']}");
			*/
			
			// By Request Payload
			$request_body = file_get_contents('php://input');
			$data = json_decode($request_body);
			$board_id = $data->board_id;
			$account = $data->account;
			$content = $data->content;
			$user = $this->session->userdata('user');
	
			$args = array('board_id'=>$board_id, 'user_id'=>$user->ID, 'content'=>$content);
			$this->board_model->insert_board_reply($args);
			
			echo json_encode(array("result"=>"success"));
		} catch (Exception $e) {
			log_message('error', $e);
			echo json_encode(array("result"=>"success"));
		}

	}

	function reply_update() {
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
	
		$this->board_model->update_board_reply(array('id'=>$data->id, 'content'=>$data->content));
	
		header("Content-Type: application/json", true);
		echo json_encode(array("result"=>"success"));
	}
	
	function reply_delete() {
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		
		$this->board_model->delete_board_reply(array('id'=>$data->id));
		
		header("Content-Type: application/json", true);
		echo json_encode(array("result"=>"success"));
	}
	
}

