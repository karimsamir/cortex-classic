@extends('cortex/foundation::adminarea.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">@lang('Universities')</h5>
                    <a href="{{ route('adminarea.universities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> @lang('Add University')
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('adminarea.universities.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('Search universities...')" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="country" class="form-select">
                                    <option value="">@lang('All Countries')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="funding" class="form-select">
                                    <option value="">@lang('All Funding Types')</option>
                                    @foreach($fundingTypes as $funding)
                                        <option value="{{ $funding }}" {{ request('funding') == $funding ? 'selected' : '' }}>{{ $funding }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">@lang('Filter')</button>
                            </div>
                        </div>
                    </form>

                    <!-- Universities Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('City')</th>
                                    <th>@lang('Funding')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universities as $university)
                                    <tr>
                                        <td>
                                            <a href="{{ route('adminarea.universities.show', $university) }}">
                                                {{ $university->name }}
                                            </a>
                                            @if($university->alt_name)
                                                <br><small class="text-muted">{{ $university->alt_name }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $university->country }}</td>
                                        <td>{{ $university->city ?? '-' }}</td>
                                        <td>{{ $university->funding ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('adminarea.universities.show', $university) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('adminarea.universities.edit', $university) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('adminarea.universities.destroy', $university) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('@lang('Are you sure?')')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">@lang('No universities found.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $universities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
