<?php
session_start();
define('SMARTY_DIR', '/home/lpburton/public_html/Smarty/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'templates/';
$smarty->compile_dir  = 'templates_c/';
$smarty->config_dir   = 'configs/';
$smarty->cache_dir    = 'cache/';

//** un-comment the following line to show the debug console
//$smarty->debugging = true;

include('includes/functions.php');

$smarty->assign('PHP_SELF', $_SERVER['PHP_SELF']);
$smarty->assign('admin', $_SESSION['is_admin']);


switch($_GET['action']){

	case 'updateinfo':

		if(!isset($_COOKIE['ChristmasCookie'])){
			unset($_GET);
			$smarty->display('login.tpl');
	
			break;
		}

		if(($_POST['new_email'] != '') && ($_POST['retyped_new_email'] != '')){
			update_email($_POST['new_email'], $_POST['retyped_new_email']);			
		}

		if(($_POST['old_password'] != '') && ($_POST['new_password'] != '') && ($_POST['retyped_new_password'] != '')){
			update_password($_POST['old_password'], $_POST['new_password'], $_POST['retyped_new_password']);
		}

		break;

	case 'additem':
		if(!isset($_COOKIE['ChristmasCookie'])){
			unset($_GET);
			$smarty->display('login.tpl');
	
			break;
		}
		add_item($_POST);
		break;

	case 'draw':
		if(!isset($_COOKIE['ChristmasCookie'])){
			unset($_GET);
			$smarty->display('login.tpl');
	
			break;
		}

		draw_name($_SESSION);
		break;

	case 'deleteitem':
		if(!isset($_COOKIE['ChristmasCookie'])){
			unset($_GET);
			$smarty->display('login.tpl');
	
			break;
		}

		delete_item($_GET['id']);

		break;


	case 'reset':

		if(!isset($_COOKIE['ChristmasCookie'])){
			unset($_GET);
			$smarty->display('login.tpl');
	
			break;
		}

		reset_db();

		break;

}



switch($_GET['page']){

	case 'register':
		$smarty->assign('names', get_names());
		if(isset($_SESSION['error'])){
			$smarty->assign('error', $_SESSION['error']);
		}
		$smarty->display('register.tpl');
		break;		

	case 'reg_confirm':
		if(register($_POST)){
			send_activation($_POST['email'], $_POST['id']);
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('register_confirm.tpl');
		}

		else{
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->assign('names', get_names());
			$smarty->display('register.tpl');
		}

		break;


	case 'activate':
		if(activate_account($_GET['id'], $_GET['code'])){
			$smarty->assign('assigned', lookup_assigned($_SESSION['assigned_person_id']));
			$smarty->assign('name', $_SESSION['name']);
		if(isset($_SESSION['error'])){
			$smarty->assign('error', $_SESSION['error']);
		}
			$smarty->display('main.tpl');
		}
		
		else{
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('login.tpl');
		}

		break;

	case 'reset_pass':
		$smarty->assign('names', get_names());
		if(isset($_SESSION['error'])){
			$smarty->assign('error', $_SESSION['error']);
		}
		$smarty->display('reset_password.tpl');

		break;

	case 'wishlist':

		if(!isset($_COOKIE['ChristmasCookie'])){
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('login.tpl');
		}

		else{

		$wishlist = get_wishlist($_SESSION['id']);
		if($wishlist){
			$smarty->assign('tr',array('bgcolor="#A7F1A7"', 'bgcolor="#FFD1D1"'));
			$smarty->assign('wishlist', $wishlist);
		}
		else{$smarty->assign('wishlist', null);}
		if(isset($_SESSION['error'])){
			$smarty->assign('error', $_SESSION['error']);
		}
		$smarty->display('wishlist.tpl');
	
	} //else

		break;

	case 'main':
		if(isset($_COOKIE['ChristmasCookie'])){

			$smarty->assign('assigned', lookup_assigned($_SESSION['assigned_person_id']));
			$smarty->assign('name', $_SESSION['name']);
			$wishlist = get_wishlist($_SESSION['assigned_person_id']);
			if($wishlist){
				$smarty->assign('tr',array('bgcolor="#A7F1A7"', 'bgcolor="#FFD1D1"'));
				$smarty->assign('wishlist', $wishlist);
			}
			else{$smarty->assign('wishlist', null);}
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('main.tpl');		
		}

		elseif(!isset($_COOKIE['ChristmasCookie']) && user_login($_POST['email'], $_POST['password'])){
			$smarty->assign('assigned', lookup_assigned($_SESSION['assigned_person_id']));
			$smarty->assign('name', $_SESSION['name']);
			unset($_POST);

			//See if the assigned person has a wishlist
			$wishlist = get_wishlist($_SESSION['assigned_person_id']);

			if($wishlist){
				//If the assigned person does have a wishlist, pass it along to the template
				$smarty->assign('tr',array('bgcolor="#A7F1A7"', 'bgcolor="#FFD1D1"'));
				$smarty->assign('wishlist', $wishlist);
			}
			//If the assigned person doesn't have a wishlist, pass a null value to the template.
			else{$smarty->assign('wishlist', null);}
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->assign('admin', $_SESSION['is_admin']);
			$smarty->display('main.tpl');
		}
		
		else{
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('login.tpl');
		}
		break;
	
	case 'infochange':
		if(!isset($_COOKIE['ChristmasCookie'])){
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('login.tpl');
		}

		else{
			$smarty->assign('session', $_SESSION);
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('infochange.tpl');
		}
		break;

	case 'logout':
		session_destroy();
		setcookie("ChristmasCookie", '', time()-3600);
		setcookie("PHPSESSID", '', time()-3600);
		$smarty->display('login.tpl');
		break;

	case 'password':
		if(!isset($_POST['email'])){
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('login.tpl');
		}

		else{
			reset_password($_POST['id'], $_POST['email']);
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->display('new_password.tpl');
		}
		break;

	case 'admin':
	
		if(!isset($_COOKIE['ChristmasCookie']) || ($_SESSION['is_admin'] != 'Y')){
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			unset($_GET);
			$smarty->display('login.tpl');
		}

		else{
			if(isset($_SESSION['error'])){
				$smarty->assign('error', $_SESSION['error']);
			}
			$smarty->assign('assigned_person', $_SESSION['assigned_person_id']);
			$smarty->display('admin.tpl');
		}
		break;

	default:
		if(isset($_SESSION['error'])){
			$smarty->assign('error', $_SESSION['error']);
		}
		$smarty->display('login.tpl');

		
}
unset($_SESSION['error']);
?>

