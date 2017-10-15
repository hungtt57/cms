

<button class="btn btn-primary btn-sm" style="margin-bottom: 10px;" onclick="export_excel()">Export to Excel</button>

<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <!-- <th>Tournament</th> !-->
        <th>User</th>
        <th>Create time</th>
        <th>Update time</th>
        <!--  <th>Status</th>!-->
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
    <tr>
        <td>{{$item->id}}</td>

        <td>{{ $item->uid }}</td>
        <td>{{ $item->created_at}}</td>
        <td>{{ $item->updated_at }}</td>

    </tr>
   @endforeach
    </tbody>
</table>

@if($num_pages > 1)
<ul class="pagination">

   @if(!empty($data->previousPageUrl()))
    <li><a href="#" onclick="pagination({{ $data->currentPage()-1  }})">&laquo;</a></li>
   @endif
     @for($i = 1;$i <= $num_pages;$i++)
    <li @if ($i == $data->currentPage()) class="active"@endif ><a href="#" onclick="pagination({{ $i }})">{{ $i }}</a>
    </li>
    @endfor
       @if(!empty($data->nextPageUrl()))
    <li><a href="#" onclick="pagination({{ $data->currentPage()+1 }})">&raquo;</a></li>
    @endif
</ul>
@endif

