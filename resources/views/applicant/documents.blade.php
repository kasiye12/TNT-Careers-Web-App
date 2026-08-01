@extends('layouts.app')
@section('title', 'Document Vault')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-folder-open text-yellow-500 mr-2"></i> Document Vault
        </h1>
        <p class="text-gray-500 mt-1">Upload and manage your documents securely</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <!-- Upload Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-6">
        <h3 class="font-bold text-lg mb-4"><i class="fas fa-upload text-[#0a7aa8] mr-2"></i> Upload New Document</h3>
        <form action="{{ route('applicant.documents.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Document Type *</label>
                <select name="document_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                    <option value="">Select Type</option>
                    <option value="cv">Curriculum Vitae (CV)</option>
                    <option value="cover_letter">Cover Letter</option>
                    <option value="degree">Degree Certificate</option>
                    <option value="transcript">Academic Transcript</option>
                    <option value="license">Professional License</option>
                    <option value="experience_letter">Experience Letter</option>
                    <option value="clearance_certificate">Clearance Certificate</option>
                    <option value="id_passport">National ID / Passport</option>
                    <option value="other">Other Document</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">File * (Max 5MB)</label>
                <input type="file" name="document" required accept=".pdf,.docx,.jpg,.jpeg,.png" 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-sky-50 file:text-[#0a7aa8] file:font-semibold">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-solid-sky w-full py-3 rounded-xl font-bold text-sm">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    @php
        $docs = isset($documents) ? $documents : (Auth::user()->applicant->documents ?? collect());
    @endphp
    
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <h3 class="font-bold">Your Documents ({{ $docs->count() }})</h3>
        </div>
        
        @if($docs->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-folder-open text-5xl mb-3 block"></i>
                <p class="font-semibold">No documents uploaded yet</p>
                <p class="text-sm">Upload your CV, certificates, and other documents</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Type</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Filename</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Size</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Uploaded</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($docs as $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($doc->document_type == 'cv') bg-blue-100 text-blue-700
                                        @elseif(in_array($doc->document_type, ['degree','transcript'])) bg-green-100 text-green-700
                                        @elseif($doc->document_type == 'license') bg-purple-100 text-purple-700
                                        @elseif($doc->document_type == 'id_passport') bg-orange-100 text-orange-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucwords(str_replace('_', ' ', $doc->document_type)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $doc->original_filename }}</td>
                                <td class="px-5 py-4 text-gray-500 text-xs">{{ number_format($doc->file_size / 1024, 1) }} KB</td>
                                <td class="px-5 py-4 text-gray-500 text-xs">{{ $doc->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('applicant.documents.download', $doc) }}" 
                                            class="p-2 text-gray-400 hover:text-[#0a7aa8] hover:bg-sky-50 rounded-lg transition" 
                                            title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('applicant.documents.delete', $doc) }}" method="POST" 
                                            onsubmit="return confirm('Delete this document? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" 
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
