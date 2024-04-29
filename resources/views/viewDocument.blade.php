@if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif')
<img src="{{url('/documentos/' . $file)}}" alt="">
@endif
@if ($ext == 'pdf' )
<iframe id="frameEa" src="{{url('/documentos/' . $file)}}" frameborder="1" style="width: 100%; height: 450vh;"></iframe>

@endif
