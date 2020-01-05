<?php
namespace helpers;
class flashmessage{

  public function __setFlash($message, $type = 'success'){
      $_SESSION['flash'] = array(
          'message' => $message,
          'type'    => $type
      );
  }

  public function __flash(){
      if(isset($_SESSION['flash'])){
          ?>
          <div class="<?php echo $_SESSION['flash']['type']; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong><?php print_r($_SESSION['flash']['message']); ?></strong>
      </div>
          <?php
          unset($_SESSION['flash']);
      }
  }

}
?>
