@extends('cortex/foundation::adminarea.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $university->name }}</h5>
                    <div>
                        <a href="{{ route('adminarea.universities.edit', $university) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> @lang('Edit')
                        </a>
                        <a href="{{ route('adminarea.universities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> @lang('Back to List')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($university->alt_name)
                        <div class="alert alert-info">
                            <strong>@lang('Alternative Name'):</strong> {{ $university->alt_name }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">@lang('Basic Information')</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>@lang('Name'):</strong></td>
                                    <td>{{ $university->name }}</td>
                                </tr>
                                @if($university->alt_name)
                                <tr>
                                    <td><strong>@lang('Alternative Name'):</strong></td>
                                    <td>{{ $university->alt_name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>@lang('Country'):</strong></td>
                                    <td>{{ $university->country }}</td>
                                </tr>
                                @if($university->state)
                                <tr>
                                    <td><strong>@lang('State/Province'):</strong></td>
                                    <td>{{ $university->state }}</td>
                                </tr>
                                @endif
                                @if($university->city)
                                <tr>
                                    <td><strong>@lang('City'):</strong></td>
                                    <td>{{ $university->city }}</td>
                                </tr>
                                @endif
                                @if($university->funding)
                                <tr>
                                    <td><strong>@lang('Funding'):</strong></td>
                                    <td>{{ $university->funding }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">@lang('Address Information')</h6>
                            <table class="table table-sm">
                                @if($university->street)
                                <tr>
                                    <td><strong>@lang('Street'):</strong></td>
                                    <td>{{ $university->street }}</td>
                                </tr>
                                @endif
                                @if($university->province)
                                <tr>
                                    <td><strong>@lang('Province'):</strong></td>
                                    <td>{{ $university->province }}</td>
                                </tr>
                                @endif
                                @if($university->postal_code)
                                <tr>
                                    <td><strong>@lang('Postal Code'):</strong></td>
                                    <td>{{ $university->postal_code }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>@lang('Full Address'):</strong></td>
                                    <td>{{ $university->full_address ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">@lang('Contact Information')</h6>
                            <table class="table table-sm">
                                @if($university->telephone)
                                <tr>
                                    <td><strong>@lang('Telephone'):</strong></td>
                                    <td>
                                        <a href="tel:{{ $university->telephone }}">{{ $university->telephone }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($university->email)
                                <tr>
                                    <td><strong>@lang('Email'):</strong></td>
                                    <td>
                                        <a href="mailto:{{ $university->email }}">{{ $university->email }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($university->website)
                                <tr>
                                    <td><strong>@lang('Website'):</strong></td>
                                    <td>
                                        <a href="{{ $university->website }}" target="_blank">{{ $university->website }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($university->fax)
                                <tr>
                                    <td><strong>@lang('Fax'):</strong></td>
                                    <td>{{ $university->fax }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">@lang('Academic Information')</h6>
                            <table class="table table-sm">
                                @if($university->languages && is_array($university->languages))
                                <tr>
                                    <td><strong>@lang('Languages'):</strong></td>
                                    <td>{{ implode(', ', $university->languages) }}</td>
                                </tr>
                                @endif
                                @if($university->academic_year)
                                <tr>
                                    <td><strong>@lang('Academic Year'):</strong></td>
                                    <td>{{ $university->academic_year }}</td>
                                </tr>
                                @endif
                                @if($university->accrediting_agency)
                                <tr>
                                    <td><strong>@lang('Accrediting Agency'):</strong></td>
                                    <td>{{ $university->accrediting_agency }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <form action="{{ route('adminarea.universities.destroy', $university) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('@lang('Are you sure you want to delete this university? This action cannot be undone.')')">
                                        <i class="fas fa-trash"></i> @lang('Delete University')
                                    </button>
                                </form>
                                <div>
                                    <a href="{{ route('adminarea.universities.edit', $university) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> @lang('Edit')
                                    </a>
                                    <a href="{{ route('adminarea.universities.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> @lang('Back to List')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
