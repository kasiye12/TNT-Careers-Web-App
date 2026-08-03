@extends('layouts.app')
@section('title', 'Professional Resume Builder')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Inter', sans-serif; }
    
    .resume-builder-container {
        background: #f0f2f5;
        min-height: calc(100vh - 64px);
        display: flex;
        flex-direction: column;
    }
    
    .main-layout {
        display: flex;
        flex: 1;
        overflow: hidden;
        height: calc(100vh - 64px);
    }
    
    /* Form Panel - Minimalist Beleqet Style */
    .form-panel {
        background: #ffffff;
        border-right: 1px solid #e8ecf0;
        overflow-y: auto;
        padding: 24px 22px 40px;
        width: 420px;
        flex-shrink: 0;
        height: 100%;
    }
    
    .form-panel::-webkit-scrollbar { width: 4px; }
    .form-panel::-webkit-scrollbar-track { background: #f1f5f9; }
    .form-panel::-webkit-scrollbar-thumb { background: #d0d5dd; border-radius: 3px; }
    
    /* Preview Panel */
    .preview-panel {
        flex: 1;
        padding: 30px;
        overflow-y: auto;
        background: #eef0f3;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        height: 100%;
    }
    
    .preview-panel::-webkit-scrollbar { width: 4px; }
    .preview-panel::-webkit-scrollbar-track { background: #eef0f3; }
    .preview-panel::-webkit-scrollbar-thumb { background: #d0d5dd; border-radius: 3px; }
    
    .resume-preview {
        width: 210mm;
        min-height: 297mm;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border-radius: 4px;
        padding: 44px 48px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    /* Section Cards - Minimalist */
    .section-card {
        background: transparent;
        border: none;
        padding: 0;
        margin-bottom: 18px;
    }
    
    .section-card:last-child { margin-bottom: 0; }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid #f0f2f5;
    }
    
    .section-header h3 {
        font-size: 11px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }
    
    .section-header .badge {
        font-size: 9px;
        font-weight: 500;
        color: #8b8b8b;
        background: #f5f5f7;
        padding: 2px 10px;
        border-radius: 12px;
    }
    
    .section-header .badge.optional {
        background: #f0f0f2;
        color: #9a9a9a;
    }
    
    .form-label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        color: #6b6b6b;
        margin-bottom: 3px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .form-label .required {
        color: #e74c3c;
        margin-left: 2px;
    }
    
    .form-label .optional-tag {
        font-weight: 400;
        color: #aaa;
        font-size: 9px;
        text-transform: none;
        margin-left: 4px;
    }
    
    .form-input {
        width: 100%;
        border: 1.5px solid #e8ecf0;
        border-radius: 6px;
        padding: 7px 12px;
        font-size: 13px;
        transition: all 0.15s ease;
        background: #fafbfc;
        color: #1a1a2e;
        font-family: 'Inter', sans-serif;
    }
    
    .form-input:focus {
        border-color: var(--theme, #4a90d9);
        box-shadow: 0 0 0 3px rgba(74,144,217,0.08);
        outline: none;
        background: #ffffff;
    }
    
    .form-input::placeholder {
        color: #b0b0b0;
        font-size: 12px;
    }
    
    .form-input.textarea {
        resize: vertical;
        min-height: 40px;
        font-size: 13px;
        line-height: 1.5;
    }
    
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }
    
    .form-group {
        margin-bottom: 10px;
    }
    
    .form-group:last-child { margin-bottom: 0; }
    
    .form-divider {
        height: 1px;
        background: #e8ecf0;
        margin: 14px 0;
    }
    
    .btn-add-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--theme, #4a90d9);
        background: transparent;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #e8ecf0;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    
    .btn-add-item:hover {
        background: #f5f7fa;
        border-color: #d0d5dd;
    }
    
    .btn-add-item i { font-size: 10px; }
    
    .btn-remove-item {
        background: none;
        border: none;
        color: #b0b0b0;
        cursor: pointer;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.15s ease;
    }
    
    .btn-remove-item:hover {
        color: #e74c3c;
        background: #fdf0ee;
    }
    
    .item-card {
        background: #fafbfc;
        border-radius: 6px;
        padding: 10px 12px;
        border: 1px solid #eef0f3;
        margin-bottom: 8px;
        position: relative;
    }
    
    .item-card:last-child { margin-bottom: 0; }
    
    .item-card .item-actions {
        position: absolute;
        top: 6px;
        right: 8px;
        display: flex;
        gap: 4px;
    }
    
    .color-theme-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }
    
    .color-theme-btn:hover { transform: scale(1.06); }
    .color-theme-btn.active { 
        border-color: #1a1a2e; 
        box-shadow: 0 0 0 2px rgba(74,144,217,0.2);
    }
    
    .color-theme-btn .check {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 9px;
        opacity: 0;
        transition: opacity 0.15s ease;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .color-theme-btn.active .check { opacity: 1; }
    
    .theme-select {
        border: 1.5px solid #e8ecf0;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 500;
        color: #1a1a2e;
        background: #fafbfc;
        width: 100%;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    
    .theme-select:focus {
        border-color: var(--theme, #4a90d9);
        outline: none;
        box-shadow: 0 0 0 3px rgba(74,144,217,0.08);
    }
    
    /* Top Bar - Minimalist */
    .top-bar {
        background: white;
        border-bottom: 1px solid #e8ecf0;
        padding: 10px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    
    .top-bar .title {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: -0.3px;
    }
    
    .top-bar .title i {
        color: var(--theme, #4a90d9);
        margin-right: 8px;
    }
    
    .top-bar .title .badge {
        font-size: 10px;
        font-weight: 500;
        color: #8b8b8b;
        background: #f5f5f7;
        padding: 2px 10px;
        border-radius: 12px;
        margin-left: 8px;
    }
    
    .top-bar .actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-action.outline {
        background: white;
        border: 1.5px solid #e8ecf0;
        color: #4a4a4a;
    }
    
    .btn-action.outline:hover {
        background: #f5f5f7;
        border-color: #d0d0d0;
    }
    
    .btn-action.primary {
        background: var(--theme, #4a90d9);
        color: white;
    }
    
    .btn-action.primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    
    .btn-action i { font-size: 12px; }
    
    /* Preview Styles - Beleqet Style */
    .preview-name {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: -0.5px;
        margin: 0;
    }
    
    .preview-title {
        font-size: 14px;
        font-weight: 400;
        color: #6b6b6b;
        margin: 4px 0 8px;
    }
    
    .preview-contact {
        font-size: 12px;
        color: #8b8b8b;
        display: flex;
        flex-wrap: wrap;
        gap: 4px 16px;
        margin-bottom: 16px;
    }
    
    .preview-contact span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .preview-contact i {
        font-size: 11px;
        color: #b0b0b0;
        width: 14px;
    }
    
    .preview-section-title {
        font-size: 12px;
        font-weight: 700;
        color: #1a1a2e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 2px 0;
    }
    
    .preview-divider {
        height: 2px;
        background: var(--theme, #4a90d9);
        width: 40px;
        margin-bottom: 10px;
    }
    
    .preview-experience-item {
        margin-bottom: 12px;
    }
    
    .preview-experience-item .exp-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .preview-experience-item .exp-company {
        font-weight: 600;
        font-size: 13px;
        color: #1a1a2e;
    }
    
    .preview-experience-item .exp-position {
        font-weight: 500;
        font-size: 12px;
        color: #4a4a4a;
    }
    
    .preview-experience-item .exp-date {
        font-size: 11px;
        color: #8b8b8b;
    }
    
    .preview-experience-item .exp-desc {
        font-size: 12px;
        color: #6b6b6b;
        line-height: 1.5;
        margin: 4px 0 0;
    }
    
    .preview-skill-tag {
        display: inline-block;
        background: #f5f5f7;
        color: #4a4a4a;
        padding: 2px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        margin: 2px 4px 2px 0;
    }
    
    .preview-language-tag {
        display: inline-block;
        background: #f5f5f7;
        color: #4a4a4a;
        padding: 2px 12px;
        border-radius: 4px;
        font-size: 11px;
        margin: 2px 4px 2px 0;
        font-weight: 500;
    }
    
    .preview-cert-tag {
        display: inline-block;
        background: #fdf0ee;
        color: #c0392b;
        padding: 2px 12px;
        border-radius: 4px;
        font-size: 11px;
        margin: 2px 4px 2px 0;
        font-weight: 500;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #b0b0b0;
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i { font-size: 48px; margin-bottom: 12px; opacity: 0.2; }
    .empty-state h4 { font-size: 16px; font-weight: 600; color: #8b8b8b; margin-bottom: 4px; }
    .empty-state p { font-size: 12px; color: #b0b0b0; }
    
    /* Social Input with prefix */
    .social-input-wrapper {
        position: relative;
    }
    
    .social-input-wrapper .social-prefix {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: #b0b0b0;
        font-weight: 400;
        pointer-events: none;
    }
    
    .social-input-wrapper .form-input {
        padding-left: 28px;
        font-size: 12px;
    }
    
    .optional-helper {
        font-size: 10px;
        color: #b0b0b0;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 10px;
    }
    
    .optional-helper i {
        font-size: 10px;
        color: #c0c0c0;
    }
    
    @media (max-width: 1024px) {
        .main-layout {
            flex-direction: column;
            overflow: visible;
            height: auto;
        }
        .form-panel { 
            width: 100%; 
            height: auto; 
            max-height: 500px; 
            border-right: none; 
            border-bottom: 1px solid #e8ecf0;
        }
        .preview-panel { 
            height: auto; 
            min-height: 500px;
            padding: 16px;
        }
        .resume-preview { 
            width: 100%; 
            min-height: 400px; 
            padding: 28px; 
        }
        .form-grid-2 { grid-template-columns: 1fr; }
        .form-grid-3 { grid-template-columns: 1fr 1fr; }
        .top-bar { 
            flex-wrap: wrap; 
            gap: 8px; 
            padding: 8px 12px; 
        }
        body { overflow: auto; }
        .resume-builder-container { height: auto; }
    }
    
    @media (max-width: 640px) {
        .form-panel { padding: 12px; max-height: 400px; }
        .preview-panel { padding: 10px; min-height: 400px; }
        .resume-preview { padding: 20px; }
        .top-bar .title { font-size: 13px; }
        .top-bar .title .badge { display: none; }
        .btn-action { padding: 4px 10px; font-size: 11px; }
        .form-grid-3 { grid-template-columns: 1fr; }
        .preview-name { font-size: 22px; }
    }
</style>
@endpush

@section('content')
<div class="resume-builder-container">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="title">
            <i class="fas fa-file-alt"></i> Resume Builder
            <span class="badge">Preview</span>
        </div>
        <div class="actions">
            <button onclick="window.print()" class="btn-action outline">
                <i class="fas fa-print"></i> Print
            </button>
            <button onclick="downloadPDF()" class="btn-action primary">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">

        <!-- FORM PANEL -->
        <div class="form-panel">

            <!-- Theme Settings -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Resume Settings</h3>
                    <span class="badge">Theme</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Theme Color</label>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <div class="color-theme-btn active" style="background: #4a90d9;" onclick="setTheme('#4a90d9', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #2c3e50;" onclick="setTheme('#2c3e50', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #27ae60;" onclick="setTheme('#27ae60', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #8e44ad;" onclick="setTheme('#8e44ad', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #e67e22;" onclick="setTheme('#e67e22', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #c0392b;" onclick="setTheme('#c0392b', this)">
                            <span class="check">✓</span>
                        </div>
                        <div class="color-theme-btn" style="background: #1abc9c;" onclick="setTheme('#1abc9c', this)">
                            <span class="check">✓</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Font</label>
                        <select id="fontSelect" onchange="updatePreview()" class="theme-select">
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Lato">Lato</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Open Sans">Open Sans</option>
                            <option value="Raleway">Raleway</option>
                            <option value="Merriweather">Merriweather</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Size</label>
                        <select id="fontSize" onchange="updatePreview()" class="theme-select">
                            <option value="compact">Compact</option>
                            <option value="standard" selected>Standard</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Personal Information -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Personal Information</h3>
                    <span class="badge">Required</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" id="fullName" oninput="updatePreview()" class="form-input" placeholder="Sal Khan" value="Sal Khan">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Cover Letter / Title</label>
                    <input type="text" id="jobTitle" oninput="updatePreview()" class="form-input" placeholder="Entrepreneur and educator..." value="Entrepreneur and educator obsessed with making education free for anyone">
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" oninput="updatePreview()" class="form-input" placeholder="hello@email.com" value="hello@beleqetjob.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" id="phone" oninput="updatePreview()" class="form-input" placeholder="+251 911 234 567" value="+251 911 234 567">
                    </div>
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" id="location" oninput="updatePreview()" class="form-input" placeholder="Addis Ababa, Ethiopia" value="Addis Ababa, Ethiopia">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="text" id="website" oninput="updatePreview()" class="form-input" placeholder="linkedin.com/company/..." value="linkedin.com/company/beleqetacademy">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Professional Summary</label>
                    <textarea id="summary" oninput="updatePreview()" class="form-input textarea" rows="2" placeholder="Brief overview..."></textarea>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Social Media Links - Optional -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Social Media</h3>
                    <span class="badge optional">Optional</span>
                </div>
                
                <div class="optional-helper">
                    <i class="fas fa-info-circle"></i> Add your social profiles
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-linkedin-in" style="color:#0A66C2;"></i> LinkedIn</label>
                        <div class="social-input-wrapper">
                            <span class="social-prefix">linkedin.com/in/</span>
                            <input type="text" id="linkedin" oninput="updatePreview()" class="form-input" placeholder="username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-github" style="color:#181717;"></i> GitHub</label>
                        <div class="social-input-wrapper">
                            <span class="social-prefix">github.com/</span>
                            <input type="text" id="github" oninput="updatePreview()" class="form-input" placeholder="username">
                        </div>
                    </div>
                </div>
                <div class="form-grid-2" style="margin-top: 8px;">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-x-twitter"></i> Twitter / X</label>
                        <div class="social-input-wrapper">
                            <span class="social-prefix">twitter.com/</span>
                            <input type="text" id="twitter" oninput="updatePreview()" class="form-input" placeholder="username">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"><i class="fab fa-facebook" style="color:#1877F2;"></i> Facebook</label>
                        <div class="social-input-wrapper">
                            <span class="social-prefix">facebook.com/</span>
                            <input type="text" id="facebook" oninput="updatePreview()" class="form-input" placeholder="username">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Work Experience -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Work Experience</h3>
                    <button onclick="addExperience()" class="btn-add-item">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                
                <div id="expContainer">
                    <div class="item-card">
                        <div class="item-actions">
                            <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company</label>
                            <input type="text" oninput="updatePreview()" class="exp-company form-input" placeholder="Company Name" value="Beleqet Academy">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Job Title</label>
                            <input type="text" oninput="updatePreview()" class="exp-position form-input" placeholder="Job Title" value="Software Engineer">
                        </div>
                        <div class="form-grid-2" style="margin-bottom: 0;">
                            <div class="form-group">
                                <label class="form-label">Start</label>
                                <input type="text" oninput="updatePreview()" class="exp-from form-input" placeholder="Jun 2022" value="Jun 2022">
                            </div>
                            <div class="form-group">
                                <label class="form-label">End</label>
                                <input type="text" oninput="updatePreview()" class="exp-to form-input" placeholder="Present" value="Present">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Education -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Education</h3>
                    <button onclick="addEducation()" class="btn-add-item">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                
                <div id="eduContainer">
                    <div class="item-card">
                        <div class="item-actions">
                            <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">School</label>
                            <input type="text" oninput="updatePreview()" class="edu-school form-input" placeholder="School Name">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Date</label>
                            <input type="text" oninput="updatePreview()" class="edu-date form-input" placeholder="2020 - 2024">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Projects -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Projects</h3>
                    <button onclick="addProject()" class="btn-add-item">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                
                <div id="projectContainer">
                    <div class="item-card">
                        <div class="item-actions">
                            <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Project Name</label>
                            <input type="text" oninput="updatePreview()" class="project-name form-input" placeholder="Project Name">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Description</label>
                            <textarea oninput="updatePreview()" class="project-desc form-input textarea" rows="2" placeholder="Project description..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            <!-- Skills -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Skills</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Skills (comma separated)</label>
                    <textarea id="skillsInput" oninput="updatePreview()" class="form-input textarea" rows="2" placeholder="e.g., JavaScript, Python, React"></textarea>
                </div>
            </div>

        </div>

        <!-- PREVIEW PANEL -->
        <div class="preview-panel">
            <div id="resumePreview" class="resume-preview" style="font-family: 'Inter', sans-serif;">
                <div id="previewContent">
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h4>Resume Preview</h4>
                        <p>Fill in the form to see your resume</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
let themeColor = '#4a90d9';
let featuredSkillsData = [];

function setTheme(color, el) {
    themeColor = color;
    document.documentElement.style.setProperty('--theme', color);
    document.querySelectorAll('.color-theme-btn').forEach(d => d.classList.remove('active'));
    if (el) el.classList.add('active');
    updatePreview();
}

function addExperience() {
    const container = document.getElementById('expContainer');
    const html = `
        <div class="item-card">
            <div class="item-actions">
                <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group">
                <label class="form-label">Company</label>
                <input type="text" oninput="updatePreview()" class="exp-company form-input" placeholder="Company Name">
            </div>
            <div class="form-group">
                <label class="form-label">Job Title</label>
                <input type="text" oninput="updatePreview()" class="exp-position form-input" placeholder="Job Title">
            </div>
            <div class="form-grid-2" style="margin-bottom: 0;">
                <div class="form-group">
                    <label class="form-label">Start</label>
                    <input type="text" oninput="updatePreview()" class="exp-from form-input" placeholder="Jun 2022">
                </div>
                <div class="form-group">
                    <label class="form-label">End</label>
                    <input type="text" oninput="updatePreview()" class="exp-to form-input" placeholder="Present">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addEducation() {
    const container = document.getElementById('eduContainer');
    const html = `
        <div class="item-card">
            <div class="item-actions">
                <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group">
                <label class="form-label">School</label>
                <input type="text" oninput="updatePreview()" class="edu-school form-input" placeholder="School Name">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Date</label>
                <input type="text" oninput="updatePreview()" class="edu-date form-input" placeholder="2020 - 2024">
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addProject() {
    const container = document.getElementById('projectContainer');
    const html = `
        <div class="item-card">
            <div class="item-actions">
                <button onclick="this.closest('.item-card').remove(); updatePreview();" class="btn-remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group">
                <label class="form-label">Project Name</label>
                <input type="text" oninput="updatePreview()" class="project-name form-input" placeholder="Project Name">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Description</label>
                <textarea oninput="updatePreview()" class="project-desc form-input textarea" rows="2" placeholder="Project description..."></textarea>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function updatePreview() {
    const name = document.getElementById('fullName').value || 'Your Name';
    const jobTitle = document.getElementById('jobTitle').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const location = document.getElementById('location').value;
    const website = document.getElementById('website').value;
    const summary = document.getElementById('summary').value;
    const skills = document.getElementById('skillsInput').value;
    const font = document.getElementById('fontSelect').value || 'Inter';
    const fontSize = document.getElementById('fontSize').value || 'standard';
    
    // Social Media
    const linkedin = document.getElementById('linkedin').value;
    const github = document.getElementById('github').value;
    const twitter = document.getElementById('twitter').value;
    const facebook = document.getElementById('facebook').value;
    
    const sizeMap = {
        compact: { base: 10, h1: 24, h3: 11, spacing: 8 },
        standard: { base: 12, h1: 28, h3: 12, spacing: 12 },
        large: { base: 14, h1: 32, h3: 14, spacing: 16 }
    };
    const s = sizeMap[fontSize] || sizeMap.standard;
    
    // Collect experiences
    let expHTML = '';
    document.querySelectorAll('#expContainer .item-card').forEach(item => {
        const company = item.querySelector('.exp-company')?.value;
        const position = item.querySelector('.exp-position')?.value;
        const from = item.querySelector('.exp-from')?.value;
        const to = item.querySelector('.exp-to')?.value;
        if (company || position) {
            expHTML += `
                <div class="preview-experience-item">
                    <div class="exp-header">
                        <span class="exp-company">${company || 'Company'}</span>
                        <span class="exp-date">${from || ''} ${from && to ? '—' : ''} ${to || ''}</span>
                    </div>
                    <div class="exp-position">${position || 'Position'}</div>
                </div>`;
        }
    });
    
    // Collect education
    let eduHTML = '';
    document.querySelectorAll('#eduContainer .item-card').forEach(item => {
        const school = item.querySelector('.edu-school')?.value;
        const date = item.querySelector('.edu-date')?.value;
        if (school) {
            eduHTML += `
                <div class="preview-experience-item" style="margin-bottom:6px;">
                    <div class="exp-header">
                        <span class="exp-company">${school}</span>
                        <span class="exp-date">${date || ''}</span>
                    </div>
                </div>`;
        }
    });
    
    // Collect projects
    let projectHTML = '';
    document.querySelectorAll('#projectContainer .item-card').forEach(item => {
        const name2 = item.querySelector('.project-name')?.value;
        const desc = item.querySelector('.project-desc')?.value;
        if (name2) {
            projectHTML += `
                <div class="preview-experience-item" style="margin-bottom:6px;">
                    <div class="exp-header">
                        <span class="exp-company">${name2}</span>
                    </div>
                    ${desc ? `<div class="exp-desc">${desc}</div>` : ''}
                </div>`;
        }
    });
    
    // Skills tags
    let skillsTags = '';
    if (skills) {
        skillsTags = skills.split(',').filter(s => s.trim()).map(s => 
            `<span class="preview-skill-tag">${s.trim()}</span>`
        ).join(' ');
    }
    
    // Social Media Links
    let socialHTML = '';
    const socialLinks = [];
    if (linkedin) socialLinks.push(`<a href="https://linkedin.com/in/${linkedin}" target="_blank" class="preview-social-link" style="display:inline-flex;align-items:center;gap:4px;color:#6b6b6b;font-size:11px;text-decoration:none;margin:0 6px 0 0;padding:2px 8px;border-radius:4px;background:#f5f5f7;"><i class="fab fa-linkedin-in"></i> LinkedIn</a>`);
    if (github) socialLinks.push(`<a href="https://github.com/${github}" target="_blank" class="preview-social-link" style="display:inline-flex;align-items:center;gap:4px;color:#6b6b6b;font-size:11px;text-decoration:none;margin:0 6px 0 0;padding:2px 8px;border-radius:4px;background:#f5f5f7;"><i class="fab fa-github"></i> GitHub</a>`);
    if (twitter) socialLinks.push(`<a href="https://twitter.com/${twitter}" target="_blank" class="preview-social-link" style="display:inline-flex;align-items:center;gap:4px;color:#6b6b6b;font-size:11px;text-decoration:none;margin:0 6px 0 0;padding:2px 8px;border-radius:4px;background:#f5f5f7;"><i class="fab fa-x-twitter"></i> Twitter</a>`);
    if (facebook) socialLinks.push(`<a href="https://facebook.com/${facebook}" target="_blank" class="preview-social-link" style="display:inline-flex;align-items:center;gap:4px;color:#6b6b6b;font-size:11px;text-decoration:none;margin:0 6px 0 0;padding:2px 8px;border-radius:4px;background:#f5f5f7;"><i class="fab fa-facebook"></i> Facebook</a>`);
    
    if (socialLinks.length > 0) {
        socialHTML = `
            <div style="margin-top: ${s.spacing}px; padding-top: ${s.spacing}px; border-top: 1px solid #eef0f3; display:flex; flex-wrap:wrap; gap:4px;">
                ${socialLinks.join('')}
            </div>
        `;
    }
    
    const contactParts = [];
    if (email) contactParts.push(`<span><i class="fas fa-envelope"></i> ${email}</span>`);
    if (phone) contactParts.push(`<span><i class="fas fa-phone"></i> ${phone}</span>`);
    if (location) contactParts.push(`<span><i class="fas fa-map-marker-alt"></i> ${location}</span>`);
    if (website) contactParts.push(`<span><i class="fas fa-globe"></i> ${website}</span>`);
    const contactStr = contactParts.join('');
    
    const hasContent = name || jobTitle || summary || expHTML || eduHTML || skills || projectHTML;
    
    if (!hasContent) {
        document.getElementById('previewContent').innerHTML = `
            <div class="empty-state" style="height:100%;">
                <i class="fas fa-file-alt"></i>
                <h4>Resume Preview</h4>
                <p>Fill in the form to see your resume</p>
            </div>
        `;
        return;
    }
    
    document.getElementById('previewContent').innerHTML = `
        <div style="font-family:'${font}',sans-serif; font-size:${s.base}px; color:#1a1a2e; line-height:1.5; max-width:100%;">
            
            <!-- Name -->
            <div class="preview-name">${name}</div>
            
            <!-- Cover Letter / Title -->
            ${jobTitle ? `<div class="preview-title">${jobTitle}</div>` : ''}
            
            <!-- Contact Info -->
            ${contactStr ? `<div class="preview-contact">${contactStr}</div>` : ''}
            
            <!-- Work Experience -->
            ${expHTML ? `
            <div style="margin-top: ${s.spacing+4}px;">
                <div class="preview-section-title">Work Experience</div>
                <div class="preview-divider"></div>
                ${expHTML}
            </div>` : ''}
            
            <!-- Projects -->
            ${projectHTML ? `
            <div style="margin-top: ${s.spacing+4}px;">
                <div class="preview-section-title">Projects</div>
                <div class="preview-divider"></div>
                ${projectHTML}
            </div>` : ''}
            
            <!-- Education -->
            ${eduHTML ? `
            <div style="margin-top: ${s.spacing+4}px;">
                <div class="preview-section-title">Education</div>
                <div class="preview-divider"></div>
                ${eduHTML}
            </div>` : ''}
            
            <!-- Skills -->
            ${skillsTags ? `
            <div style="margin-top: ${s.spacing+4}px;">
                <div class="preview-section-title">Skills</div>
                <div class="preview-divider"></div>
                <div style="display:flex; flex-wrap:wrap; gap:2px;">${skillsTags}</div>
            </div>` : ''}
            
            <!-- Social Media -->
            ${socialHTML}
            
        </div>
    `;
    
    document.getElementById('resumePreview').style.fontFamily = `'${font}', sans-serif`;
}

function downloadPDF() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("resume.generate") }}';
    const previewHTML = document.getElementById('previewContent').innerHTML;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="full_name" value="${document.getElementById('fullName').value}">
        <input type="hidden" name="email" value="${document.getElementById('email').value}">
        <input type="hidden" name="theme_color" value="${themeColor}">
        <input type="hidden" name="font_family" value="${document.getElementById('fontSelect').value}">
        <input type="hidden" name="font_size" value="${document.getElementById('fontSize').value}">
        <input type="hidden" name="job_title" value="${document.getElementById('jobTitle').value}">
        <input type="hidden" name="phone" value="${document.getElementById('phone').value}">
        <input type="hidden" name="location" value="${document.getElementById('location').value}">
        <input type="hidden" name="website" value="${document.getElementById('website').value}">
        <input type="hidden" name="summary" value="${document.getElementById('summary').value}">
        <input type="hidden" name="skills" value="${document.getElementById('skillsInput').value}">
        <input type="hidden" name="linkedin" value="${document.getElementById('linkedin').value}">
        <input type="hidden" name="github" value="${document.getElementById('github').value}">
        <input type="hidden" name="twitter" value="${document.getElementById('twitter').value}">
        <input type="hidden" name="facebook" value="${document.getElementById('facebook').value}">
        <input type="hidden" name="preview_html" value="${encodeURIComponent(previewHTML)}">
    `;
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Initialize
document.documentElement.style.setProperty('--theme', themeColor);
updatePreview();
</script>
@endsection