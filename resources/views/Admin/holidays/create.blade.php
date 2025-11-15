@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-calendar-plus me-2"></i> Add Holiday
        </h2>
        <p class="text-light small opacity-75">
            Create a new holiday and specify its type, date, and restricted users (if applicable).
        </p>
    </div>

    <!-- Create Holiday Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New Holiday</h5>
            <a href="{{ route('admin.holidays.index') }}" class="btn btn-premium-outline">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show glass-card mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ✅ Form --}}
        <form action="{{ route('admin.holidays.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="row g-4">
                <!-- Holiday Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-gift-fill me-1 text-warning"></i> Holiday Name
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control glass-input text-white" 
                        value="{{ old('name') }}" 
                        placeholder="Enter holiday name" 
                        required>
                    <div class="invalid-feedback text-white">Please enter a holiday name.</div>
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-calendar-event-fill me-1 text-warning"></i> Date
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        class="form-control glass-input text-white" 
                        value="{{ old('date') }}" 
                        required>
                    <div class="invalid-feedback text-white">Please select a valid date.</div>
                </div>

                <!-- Type -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-tags-fill me-1 text-warning"></i> Type
                    </label>
                    <select 
                        name="type" 
                        id="holidayType" 
                        class="form-select glass-input select-dark text-white fw-semibold" 
                        required>
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback text-white">Please select a holiday type.</div>
                </div>

                <!-- Restricted Users -->
                <div class="col-md-6" id="restrictedUsers" style="display: none;">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-people-fill me-1 text-warning"></i> Restricted Holiday Users
                    </label>

                    <div class="tag-box" id="tagBox">
                        <input type="text" class="tag-input" id="tagInput" placeholder="Search or select users...">
                        <div class="user-dropdown-list" id="dropdown"></div>
                    </div>

                    <small class="text-white d-block mt-2">
                        Click to select multiple users. Use × to remove a user.
                    </small>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-card-text me-1 text-warning"></i> Description
                    </label>
                    <textarea 
                        name="description" 
                        class="form-control glass-input text-white" 
                        rows="3" 
                        placeholder="Optional">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Create
                </button>
                <a href="{{ route('admin.holidays.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ✅ JavaScript --}}
<script>
(() => {
    'use strict';

    // ✅ Bootstrap Validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // 🎯 Toggle Restricted Users
    const typeSelect = document.getElementById('holidayType');
    const restrictedDiv = document.getElementById('restrictedUsers');
    function toggleUserSelect() {
        restrictedDiv.style.display = (typeSelect.value === 'restricted') ? 'block' : 'none';
    }
    typeSelect.addEventListener('change', toggleUserSelect);
    toggleUserSelect();

    // 🎯 Tag Select Logic (multi user fix)
    const users = @json($users->pluck('name', 'id'));
    const tagBox = document.getElementById('tagBox');
    const input = document.getElementById('tagInput');
    const dropdown = document.getElementById('dropdown');
    let selected = [];

    input.addEventListener('focus', () => showOptions(Object.entries(users)));
    input.addEventListener('input', () => {
        const val = input.value.toLowerCase();
        const filtered = Object.entries(users)
            .filter(([id, name]) => name.toLowerCase().includes(val) && !selected.includes(parseInt(id)));
        showOptions(filtered);
    });

    function showOptions(list) {
        dropdown.innerHTML = '';
        dropdown.style.display = list.length ? 'block' : 'none';
        list.forEach(([id, name]) => {
            const div = document.createElement('div');
            div.textContent = name;
            div.addEventListener('click', () => addTag(parseInt(id), name));
            dropdown.appendChild(div);
        });
    }

    function addTag(id, name) {
        if (selected.includes(id)) return;
        selected.push(id);
        renderTags();
        input.value = '';
        dropdown.style.display = 'none';
    }

    function removeTag(id) {
        selected = selected.filter(v => v !== id);
        renderTags();
    }

    function renderTags() {
        // Remove existing tags & hidden inputs
        tagBox.querySelectorAll('.tag').forEach(tag => tag.remove());
        tagBox.querySelectorAll('input[name="users[]"]').forEach(el => el.remove());

        // Add tags + hidden inputs
        selected.forEach(id => {
            const tag = document.createElement('div');
            tag.classList.add('tag');
            tag.textContent = users[id];
            const close = document.createElement('span');
            close.textContent = '×';
            close.addEventListener('click', () => removeTag(id));
            tag.appendChild(close);
            tagBox.insertBefore(tag, input);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'users[]';
            hidden.value = id;
            tagBox.appendChild(hidden);
        });
    }

    document.addEventListener('click', e => {
        if (!tagBox.contains(e.target)) dropdown.style.display = 'none';
    });
})();
</script>

{{-- ✅ Styles --}}
<style>
.page-title { color: #fff; }

.dashboard-card {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    color: #fff;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4);
}

.glass-input {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
}
.glass-input:focus {
    background: rgba(255,255,255,0.15);
    border-color: #ff416c;
    box-shadow: 0 0 10px rgba(255,65,108,0.4);
}
.glass-input::placeholder,
textarea.glass-input::placeholder { color: rgba(255,255,255,0.7); }

.select-dark {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.select-dark:focus {
    background: rgba(255,255,255,0.15);
    border-color: #ff416c;
    box-shadow: 0 0 10px rgba(255,65,108,0.4);
    color: #fff;
}
.select-dark option {
    background-color: #fff !important;
    color: #000 !important;
}

.btn-premium {
    background: linear-gradient(90deg, #00b09b, #96c93d);
    border: none;
    border-radius: 999px;
    padding: 6px 16px;
    color: #fff;
    font-weight: 600;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,65,108,0.3);
}
.btn-premium-outline {
    background: transparent;
    border: 2px solid rgba(255,255,255,0.4);
    color: #fff;
    border-radius: 999px;
    padding: 6px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-premium-outline:hover {
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.6);
}

.tag-box {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 8px;
    transition: 0.2s ease;
}
.tag-input {
    border: none;
    outline: none;
    flex: 1;
    min-width: 100px;
    background: transparent;
    color: #fff;
    font-size: 15px;
}
.tag-input::placeholder { color: rgba(255,255,255,0.7); }

.tag {
    background-color: #007bff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    font-size: 14px;
    animation: fadeIn 0.2s ease;
}
.tag span {
    margin-left: 8px;
    cursor: pointer;
    font-weight: bold;
}
.tag span:hover { color: #ffd6d6; }

.user-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 4px;
    width: 100%;
    background: rgba(255,255,255,0.95);
    color: #000;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.25);
    max-height: 160px;
    overflow-y: auto;
    display: none;
    z-index: 1050;
}
.user-dropdown-list div {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}
.user-dropdown-list div:hover {
    background-color: #f0f8ff;
    color: #007bff;
}

@keyframes fadeIn {
    from {opacity: 0; transform: scale(0.95);}
    to {opacity: 1; transform: scale(1);}
}
</style>
@endsection
