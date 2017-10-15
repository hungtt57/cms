@extends('_layouts/default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Message</th>
                            <th>Link</th>
                            <th>Location</th>
                            <th>Join count</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surveys as $survey)
                            <tr>
                                <td>
                                    <img src="{{ get_image_url($survey->image) }}" alt="Image" class="img-rounded" width="64" height="64">
                                </td>
                                <td>{{ $survey->message }}</td>
                                <td><a href="{{ $survey->link }}" target="_blank">{{ $survey->link }}</a></td>
                                <td>{{ is_array($survey->location) ? implode(', ', $survey->location) : '' }}</td>
                                <td>{{ $survey->join_count or 0 }}</td>
                                <td>
                                    <input type="checkbox" value="{{ $survey->status }}">
                                </td>
                                <td>
                                    <a href="{{ route('Staff::Management::settings@grouptype.edit', $survey->id) }}">
                                        <button type="submit">Edit</button>
                                    </a>
                                    <form action="{{ route('Staff::Management::settings@grouptype.delete', $survey->id) }}" method="post" style="display: inline-block">
                                        {!! method_field('DELETE') !!}
                                        <input type="submit" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
@endsection