<?php
// $param2 = lesson id and $param3 = course id
$lesson_details = $this->crud_model->get_lessons('lesson', $param2)->row_array();
$sections = $this->crud_model->get_section('course', $param3)->result_array();
?>

<form action="<?php echo site_url('admin/lessons/'.$param3.'/edit'.'/'.$param2); ?>" method="post" enctype="multipart/form-data">

  <div class="form-group">
    <label><?php echo get_phrase('title'); ?></label>
    <input type="text" name = "title" class="form-control" required value="<?php echo $lesson_details['title']; ?>">
  </div>

  <input type="hidden" name="course_id" value="<?php echo $param3; ?>">

  <div class="form-group">
    <label for="section_id"><?php echo get_phrase('section'); ?></label>
    <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
      <?php foreach ($sections as $section): ?>
        <option value="<?php echo $section['id']; ?>" <?php if($lesson_details['section_id'] == $section['id']) echo 'selected'; ?>><?php echo $section['title']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="section_id"><?php echo get_phrase('lesson_type'); ?></label>
    <select class="form-control select2" data-toggle="select2" name="lesson_type" id="lesson_type" required onchange="show_lesson_type_form(this.value)">
      <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
      <option value="video-url" <?php if($lesson_details['attachment_type'] == 'url' || $lesson_details['attachment_type'] == '') echo 'selected'; ?>><?php echo get_phrase('text'); ?></option>
      <option value="other-txt" <?php if($lesson_details['attachment_type'] == 'txt') echo 'selected'; ?>><?php echo get_phrase('text_file'); ?></option>
      <option value="other-pdf" <?php if($lesson_details['attachment_type'] == 'pdf') echo 'selected'; ?>><?php echo get_phrase('pdf_file'); ?></option>
      <option value="other-doc" <?php if($lesson_details['attachment_type'] == 'doc') echo 'selected'; ?>><?php echo get_phrase('document_file'); ?></option>
      <option value="other-img" <?php if($lesson_details['attachment_type'] == 'img') echo 'selected'; ?>><?php echo get_phrase('image_file'); ?></option>
    </select>
  </div>

  <div class="" id="video" <?php if($lesson_details['lesson_type'] != 'video'):?> style="display: none;" <?php endif; ?>>


   


    <!-- This portion is for mobile application video lesson -->
   


  </div>

  <div class="" id = "other" <?php if($lesson_details['lesson_type'] != 'other'):?> style="display: none;" <?php endif; ?>>
    <div class="form-group">
      <label><?php echo get_phrase('attachment'); ?></label>
      <div class="input-group">
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="attachment" name="attachment" onchange="changeTitleOfImageUploader(this)">
          <label class="custom-file-label" for="attachment"><?php echo get_phrase('attachment'); ?></label>
        </div>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label>Text</label>
    <textarea name="summary" id="summary" class="form-control"><?php echo $lesson_details['summary']; ?></textarea>
  </div>

  <div class="text-center">
    <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('update_lesson'); ?></button>
  </div>

</form>
  <script type="text/javascript">
  $(document).ready(function () {
    initSummerNote(['#summary']);
  });
</script>
<script type="text/javascript">

$(document).ready(function() {
  initSelect2(['#section_id','#lesson_type', '#lesson_provider', '#lesson_provider_for_mobile_application']);
  initTimepicker();
  show_lesson_type_form($('#lesson_type').val());
});

function ajax_get_section(course_id) {
  $.ajax({
    url: '<?php echo site_url('admin/ajax_get_section/');?>' + course_id ,
    success: function(response)
    {
      jQuery('#section_id').html(response);
    }
  });
}

function ajax_get_video_details(video_url) {
  $('#perloader').show();
  if(checkURLValidity(video_url)){
    $.ajax({
      url: '<?php echo site_url('admin/ajax_get_video_details');?>',
      type : 'POST',
      data : {video_url : video_url},
      success: function(response)
      {
        jQuery('#duration').val(response);
        $('#perloader').hide();
        $('#invalid_url').hide();
      }
    });
  }else {
    $('#invalid_url').show();
    $('#perloader').hide();
    jQuery('#duration').val('');
  }
}

function checkURLValidity(video_url) {
  var youtubePregMatch = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
  var vimeoPregMatch = /^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/;
  if (video_url.match(youtubePregMatch)) {
    return true;
  }
  else if (vimeoPregMatch.test(video_url)) {
    return true;
  }
  else {
    return false;
  }
}

function show_lesson_type_form(param) {
  var checker = param.split('-');
  var lesson_type = checker[0];
  if (lesson_type === "video") {
    $('#other').hide();
    $('#video').show();
  }else if (lesson_type === "other") {
    $('#video').hide();
    $('#other').show();
  }else {
    $('#video').hide();
    $('#other').hide();
  }
}

function check_video_provider(provider) {
  if (provider === 'youtube' || provider === 'vimeo') {
    $('#html5').hide();
    $('#youtube_vimeo').show();
  }else if(provider === 'html5'){
    console.log(provider);
    $('#youtube_vimeo').hide();
    $('#html5').show();
  }else {
    $('#youtube_vimeo').hide();
    $('#html5').hide();
  }
}
</script>
