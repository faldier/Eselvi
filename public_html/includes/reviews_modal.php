
<div class="modal fade " id="review_modal" tabindex="-1"  aria-labelledby="review_modal" aria-hidden="true">
  <div class="modal-dialog modal-xl" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid row">
          <div class="col-md-6" >
              <h3 ><?=$product['title'] ?></h3>
            <img class="ml-5" width="200px" src="<?=$productImages[0];?>" />
          </div>
          <div class="col-md-6">
            <form class="" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
            <h5>Click to rate:</h5>
            <div class='starrr' id='star1'></div>
            <div>&nbsp;
              <span class='your-choice-was' style='display: none;'>
                Your rating was <span class='choice'></span>.
              </span>
              <input type="hidden" name="vote" id="vote" class="choice" >
            </div>
            <br>
          <textarea id="review_text" name="review_text" class="col-md-10"name="name" rows="8" placeholder="leave your comment here"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" name="" value="Add Review">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $('#star1').starrr({
    change: function(e, value){
      if (value) {
        $('.your-choice-was').show();
        $('.choice').text(value);
        $('.choice').val(value);
      } else {
        $('.your-choice-was').hide();
      }
    }
  });
</script>
