@if(!empty($name))

					<tr id="{{$id}}">

						<td >{{ $name}}</td>
						<td>{{ html_entity_decode(HTML::linkRoute('site.show',HTML::image('images/assign_icon.png','Assign Users', array('class'=>"img-responsive")),[$id])) }}
						</td>
						
						<?php $val = array( 'class'=>'btn-link edit');?>

						<td>{{ Form::button('Edit',$val) }}</td> 

						<?php $vall =array('class'=>'btn-link delete');?>

						<td>{{ Form::button('Delete',$vall) }}</td>

					</tr>

					@endif

					



					@if(!empty($update_name))

					

						<td >{{ $update_name}}</td>

						

						<td>{{ html_entity_decode(HTML::linkRoute('site.show',HTML::image('images/assign_icon.png','Assign Users', array('class'=>"img-responsive")),[$update_id])) }}</td>
						
						<?php $val = array( 'class'=>'btn-link edit');?>

						<td>{{ Form::button('Edit',$val) }}</td> 

						<?php $vall =array('class'=>'btn-link delete');?>

						<td>{{ Form::button('Delete',$vall) }}</td>

					

					@endif



					@if(!empty($exception))



						{{'exception'}}

					



					@endif