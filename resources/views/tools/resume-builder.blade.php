@extends('layouts.app')
@section('title', 'Professional Resume Builder')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Lato:wght@300;400;700;900&family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;600;700;800&family=Raleway:wght@300;400;500;600;700;800&family=Merriweather:wght@300;400;700;900&family=Playfair+Display:wght@400;500;600;700;800&family=Caladea:wght@400;700&family=Lora:wght@400;500;600;700&family=Roboto+Slab:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --theme: #2563eb;
    }
    .resume-preview {
        width: 210mm;
        min-height: 297mm;
        background: white;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .form-input {
        @apply w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm transition-all duration-200;
        background: #f8fafc;
    }
    .form-input:focus {
        @apply border-blue-500 bg-white;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
        outline: none;
    }
    .form-section {
        @apply bg-white rounded-2xl p-6 shadow-sm border border-gray-100 transition-all duration-200;
    }
    .form-section:hover {
        @apply border-blue-200 shadow-md;
    }
    .color-dot {
        width: 32px; height: 32px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
        border: 3px solid transparent;
    }
    .color-dot:hover { transform: scale(1.15); }
    .color-dot.active { border-color: #1e293b; box-shadow: 0 0 0 3px rgba(37,99,235,0.3); }
    
    .skill-circle {
        width: 28px; height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid #e2e8f0;
        transition: all 0.2s;
        background: white;
        color: #94a3b8;
    }
    .skill-circle.filled {
        background: var(--theme);
        border-color: var(--theme);
        color: white;
    }
    
    .preview-header {
        transition: all 0.3s ease;
    }
    
    .section-divider {
        height: 2px;
        background: linear-gradient(to right, var(--theme), transparent);
        margin: 8px 0 16px;
    }
    
    .btn-add {
        @apply text-blue-600 text-sm font-semibold hover:text-blue-800 transition-colors flex items-center gap-1;
    }
    
    .scrollbar-thin::-webkit-scrollbar { width: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    
    @media print {
        body * { visibility: hidden; }
        .resume-preview, .resume-preview * { visibility: visible; }
        .resume-preview { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Top Bar -->
    <div class="bg-white border-b sticky top-16 z-40 shadow-sm">
        <div class="max-w-full mx-auto px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-extrabold text-gray-900">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>Resume Builder
                </h1>
                <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Live Preview</span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <button onclick="downloadPDF()" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/25">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-0">
        <!-- LEFT: Form Panel -->
        <div class="lg:w-[480px] xl:w-[520px] bg-white border-r overflow-y-auto scrollbar-thin" style="height: calc(100vh - 140px);">
            <div class="p-5 space-y-4">
                
                <!-- Theme Settings -->
                <div class="form-section">
                    <h3 class="font-bold text-sm text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-palette text-blue-500"></i> Theme Settings
                    </h3>
                    <div class="flex flex-wrap gap-3 mb-3">
                        <div class="color-dot active" style="background:#2563eb" onclick="setTheme('#2563eb', this)" title="Blue"></div>
                        <div class="color-dot" style="background:#059669" onclick="setTheme('#059669', this)" title="Green"></div>
                        <div class="color-dot" style="background:#7c3aed" onclick="setTheme('#7c3aed', this)" title="Purple"></div>
                        <div class="color-dot" style="background:#dc2626" onclick="setTheme('#dc2626', this)" title="Red"></div>
                        <div class="color-dot" style="background:#0f172a" onclick="setTheme('#0f172a', this)" title="Dark"></div>
                        <div class="color-dot" style="background:#ea580c" onclick="setTheme('#ea580c', this)" title="Orange"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Font</label>
                            <select onchange="updatePreview()" id="fontSelect" class="form-input py-2 text-xs">
                                <option value="Roboto">Roboto</option>
                                <option value="Lato">Lato</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Open Sans">Open Sans</option>
                                <option value="Raleway">Raleway</option>
                                <option value="Merriweather">Merriweather</option>
                                <option value="Playfair Display">Playfair Display</option>
                                <option value="Caladea">Caladea</option>
                                <option value="Lora">Lora</option>
                                <option value="Roboto Slab">Roboto Slab</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Size</label>
                            <select onchange="updatePreview()" id="fontSize" class="form-input py-2 text-xs">
                                <option value="compact">Compact</option>
                                <option value="standard" selected>Standard</option>
                                <option value="large">Large</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="form-section">
                    <h3 class="font-bold text-sm text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-user text-blue-500"></i> Personal Information
                    </h3>
                    <div class="space-y-2.5">
                        <input type="text" oninput="updatePreview()" id="fullName" class="form-input" placeholder="Full Name *">
                        <input type="text" oninput="updatePreview()" id="jobTitle" class="form-input" placeholder="Professional Title (e.g., Senior Engineer)">
                        <div class="grid grid-cols-2 gap-2">
                            <input type="email" oninput="updatePreview()" id="email" class="form-input" placeholder="Email">
                            <input type="text" oninput="updatePreview()" id="phone" class="form-input" placeholder="Phone">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" oninput="updatePreview()" id="location" class="form-input" placeholder="Location">
                            <input type="text" oninput="updatePreview()" id="website" class="form-input" placeholder="LinkedIn / Website">
                        </div>
                        <textarea oninput="updatePreview()" id="summary" rows="3" class="form-input" placeholder="Professional Summary - Brief overview of your experience and goals..."></textarea>
                    </div>
                </div>

                <!-- Experience -->
                <div class="form-section">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-sm text-gray-700 flex items-center gap-2">
                            <i class="fas fa-briefcase text-purple-500"></i> Work Experience
                        </h3>
                        <button onclick="addExperience()" class="btn-add"><i class="fas fa-plus-circle"></i> Add</button>
                    </div>
                    <div id="expContainer" class="space-y-3">
                        <div class="exp-item bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <div class="space-y-2">
                                <input type="text" oninput="updatePreview()" class="exp-company form-input py-2 text-xs" placeholder="Company Name">
                                <input type="text" oninput="updatePreview()" class="exp-position form-input py-2 text-xs" placeholder="Job Title">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" oninput="updatePreview()" class="exp-from form-input py-2 text-xs" placeholder="Start (e.g., Jan 2020)">
                                    <input type="text" oninput="updatePreview()" class="exp-to form-input py-2 text-xs" placeholder="End (e.g., Present)">
                                </div>
                                <textarea oninput="updatePreview()" class="exp-desc form-input py-2 text-xs" rows="2" placeholder="Key responsibilities & achievements..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div class="form-section">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-sm text-gray-700 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-green-500"></i> Education
                        </h3>
                        <button onclick="addEducation()" class="btn-add"><i class="fas fa-plus-circle"></i> Add</button>
                    </div>
                    <div id="eduContainer" class="space-y-3">
                        <div class="edu-item bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <div class="space-y-2">
                                <input type="text" oninput="updatePreview()" class="edu-school form-input py-2 text-xs" placeholder="Institution">
                                <input type="text" oninput="updatePreview()" class="edu-degree form-input py-2 text-xs" placeholder="Degree & Major">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" oninput="updatePreview()" class="edu-date form-input py-2 text-xs" placeholder="Year">
                                    <input type="text" oninput="updatePreview()" class="edu-gpa form-input py-2 text-xs" placeholder="GPA (optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skills -->
                <div class="form-section">
                    <h3 class="font-bold text-sm text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i> Skills
                    </h3>
                    <textarea oninput="updatePreview()" id="skillsInput" rows="2" class="form-input" placeholder="Skills separated by commas...&#10;e.g., Project Management, AutoCAD, Leadership"></textarea>
                    
                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Featured Skills (with proficiency)</label>
                        <div id="featuredSkills" class="space-y-2"></div>
                        <div class="flex gap-2 mt-2">
                            <input type="text" id="newSkill" class="form-input py-2 text-xs flex-1" placeholder="Add featured skill..." onkeypress="if(event.key==='Enter'){addFeaturedSkill();return false}">
                            <button onclick="addFeaturedSkill()" class="px-3 py-2 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-600">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Languages -->
                <div class="form-section">
                    <h3 class="font-bold text-sm text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-language text-indigo-500"></i> Languages
                    </h3>
                    <textarea oninput="updatePreview()" id="languagesInput" rows="2" class="form-input" placeholder="Languages...&#10;e.g., Amharic (Native), English (Fluent), French (Intermediate)"></textarea>
                </div>

                <!-- Certifications -->
                <div class="form-section">
                    <h3 class="font-bold text-sm text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-certificate text-orange-500"></i> Certifications
                    </h3>
                    <textarea oninput="updatePreview()" id="certsInput" rows="2" class="form-input" placeholder="Certifications...&#10;e.g., PMP, EAEA Registered, NEBOSH"></textarea>
                </div>

            </div>
        </div>

        <!-- RIGHT: Live Preview -->
        <div class="flex-1 bg-gray-200 flex items-start justify-center p-6 overflow-y-auto" style="height: calc(100vh - 140px);">
            <div id="resumePreview" class="resume-preview" style="font-family: 'Roboto', sans-serif;">
                <div id="previewContent" class="p-10" style="min-height: 800px;">
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center h-full text-gray-300 py-32">
                        <i class="fas fa-file-alt text-6xl mb-4"></i>
                        <p class="text-lg font-medium">Resume Preview</p>
                        <p class="text-sm">Start filling the form to see your resume</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let themeColor = '#2563eb';
let featuredSkillsData = [];

function setTheme(color, el) {
    themeColor = color;
    document.documentElement.style.setProperty('--theme', color);
    document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
    if (el) el.classList.add('active');
    updatePreview();
}

function addFeaturedSkill() {
    const input = document.getElementById('newSkill');
    const name = input.value.trim();
    if (!name) return;
    featuredSkillsData.push({name, level: 5});
    input.value = '';
    renderFeaturedSkills();
    updatePreview();
}

function renderFeaturedSkills() {
    const container = document.getElementById('featuredSkills');
    container.innerHTML = featuredSkillsData.map((s, i) => `
        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
            <span class="text-xs font-semibold text-gray-700">${s.name}</span>
            <div class="flex items-center gap-2">
                <div class="flex gap-0.5">
                    ${[1,2,3,4,5].map(l => `
                        <span class="skill-circle ${l <= s.level ? 'filled' : ''}" 
                              onclick="setSkillLevel(${i}, ${l})" 
                              style="${l <= s.level ? 'background:'+themeColor+';border-color:'+themeColor : ''}">
                            ●
                        </span>
                    `).join('')}
                </div>
                <button onclick="featuredSkillsData.splice(${i},1);renderFeaturedSkills();updatePreview()" class="text-red-400 hover:text-red-600 text-xs">×</button>
            </div>
        </div>
    `).join('');
}

function setSkillLevel(index, level) {
    featuredSkillsData[index].level = level;
    renderFeaturedSkills();
    updatePreview();
}

function addExperience() {
    document.getElementById('expContainer').insertAdjacentHTML('beforeend', `
        <div class="exp-item bg-gray-50 rounded-xl p-3 border border-gray-100">
            <button onclick="this.parentElement.remove();updatePreview()" class="float-right text-red-400 hover:text-red-600 text-xs mb-1">× Remove</button>
            <div class="space-y-2">
                <input type="text" oninput="updatePreview()" class="exp-company form-input py-2 text-xs" placeholder="Company Name">
                <input type="text" oninput="updatePreview()" class="exp-position form-input py-2 text-xs" placeholder="Job Title">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" oninput="updatePreview()" class="exp-from form-input py-2 text-xs" placeholder="Start">
                    <input type="text" oninput="updatePreview()" class="exp-to form-input py-2 text-xs" placeholder="End">
                </div>
                <textarea oninput="updatePreview()" class="exp-desc form-input py-2 text-xs" rows="2" placeholder="Description..."></textarea>
            </div>
        </div>`);
}

function addEducation() {
    document.getElementById('eduContainer').insertAdjacentHTML('beforeend', `
        <div class="edu-item bg-gray-50 rounded-xl p-3 border border-gray-100">
            <button onclick="this.parentElement.remove();updatePreview()" class="float-right text-red-400 hover:text-red-600 text-xs mb-1">× Remove</button>
            <div class="space-y-2">
                <input type="text" oninput="updatePreview()" class="edu-school form-input py-2 text-xs" placeholder="Institution">
                <input type="text" oninput="updatePreview()" class="edu-degree form-input py-2 text-xs" placeholder="Degree & Major">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" oninput="updatePreview()" class="edu-date form-input py-2 text-xs" placeholder="Year">
                    <input type="text" oninput="updatePreview()" class="edu-gpa form-input py-2 text-xs" placeholder="GPA">
                </div>
            </div>
        </div>`);
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
    const languages = document.getElementById('languagesInput').value;
    const certs = document.getElementById('certsInput').value;
    const font = document.getElementById('fontSelect').value || 'Roboto';
    const fontSize = document.getElementById('fontSize').value || 'standard';
    
    const sizeMap = {compact: {base: 10, h1: 22, h3: 13}, standard: {base: 11.5, h1: 26, h3: 15}, large: {base: 13, h1: 30, h3: 17}};
    const s = sizeMap[fontSize];
    
    // Collect experiences
    let expHTML = '';
    document.querySelectorAll('.exp-item').forEach(item => {
        const company = item.querySelector('.exp-company')?.value;
        const position = item.querySelector('.exp-position')?.value;
        const from = item.querySelector('.exp-from')?.value;
        const to = item.querySelector('.exp-to')?.value;
        const desc = item.querySelector('.exp-desc')?.value;
        if (company || position) {
            expHTML += `
                <div style="margin-bottom:14px;padding-left:14px;border-left:3px solid ${themeColor}">
                    <div style="display:flex;justify-content:space-between;align-items:baseline">
                        <strong style="font-size:${s.base+1}px">${position || 'Position'}</strong>
                        <span style="color:#888;font-size:${s.base-1}px">${from || ''} ${from&&to?'-':''} ${to || ''}</span>
                    </div>
                    <div style="color:${themeColor};font-weight:600;font-size:${s.base}px;margin-top:1px">${company || 'Company'}</div>
                    ${desc ? `<p style="color:#555;font-size:${s.base-1}px;margin-top:4px;line-height:1.5">${desc}</p>` : ''}
                </div>`;
        }
    });
    
    // Collect education
    let eduHTML = '';
    document.querySelectorAll('.edu-item').forEach(item => {
        const school = item.querySelector('.edu-school')?.value;
        const degree = item.querySelector('.edu-degree')?.value;
        const date = item.querySelector('.edu-date')?.value;
        const gpa = item.querySelector('.edu-gpa')?.value;
        if (school || degree) {
            eduHTML += `
                <div style="margin-bottom:8px">
                    <strong style="font-size:${s.base}px">${degree || 'Degree'}</strong>${gpa ? ' <span style="color:#888;font-size:'+(s.base-1)+'px">| GPA: '+gpa+'</span>' : ''}
                    <br><span style="color:#666;font-size:${s.base-1}px">${school || 'Institution'} ${date ? '('+date+')' : ''}</span>
                </div>`;
        }
    });
    
    // Featured skills
    let featHTML = featuredSkillsData.map(sk => {
        const pct = (sk.level/5)*100;
        return `
            <div style="margin-bottom:6px">
                <div style="display:flex;justify-content:space-between;font-size:${s.base-1}px">
                    <span>${sk.name}</span><span style="color:#888">${sk.level}/5</span>
                </div>
                <div style="background:#e5e7eb;height:5px;border-radius:3px;margin-top:2px">
                    <div style="background:${themeColor};height:5px;border-radius:3px;width:${pct}%"></div>
                </div>
            </div>`;
    }).join('');
    
    // Skills tags
    let skillsTags = skills ? skills.split(',').map(s => 
        `<span style="display:inline-block;background:${themeColor}15;color:${themeColor};padding:2px 10px;border-radius:12px;font-size:${s.base-2}px;font-weight:600;margin:2px">${s.trim()}</span>`
    ).join(' ') : '';
    
    const contactParts = [email, phone, location, website].filter(Boolean).join(' <span style="color:#ccc;margin:0 4px">|</span> ');
    
    document.getElementById('previewContent').innerHTML = `
        <div style="font-family:'${font}',sans-serif;font-size:${s.base}px;color:#1e293b;line-height:1.5;max-width:100%">
            
            <!-- HEADER -->
            <div style="text-align:center;margin-bottom:20px;padding-bottom:16px;border-bottom:3px solid ${themeColor}">
                <h1 style="font-size:${s.h1}px;font-weight:800;margin:0;letter-spacing:-1px;color:#0f172a;text-transform:uppercase">${name}</h1>
                ${jobTitle ? `<p style="font-size:${s.base+1}px;color:${themeColor};font-weight:500;margin:4px 0 8px">${jobTitle}</p>` : ''}
                <p style="color:#64748b;font-size:${s.base-1}px;margin:0">${contactParts || 'Contact information'}</p>
            </div>
            
            ${summary ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Professional Summary</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                <p style="color:#475569;font-size:${s.base}px;line-height:1.6">${summary}</p>
            </div>` : ''}
            
            ${expHTML ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Work Experience</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                ${expHTML}
            </div>` : ''}
            
            ${eduHTML ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Education</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                ${eduHTML}
            </div>` : ''}
            
            ${featHTML ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Featured Skills</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                ${featHTML}
            </div>` : ''}
            
            ${skills ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Skills</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                <p style="line-height:2">${skillsTags}</p>
            </div>` : ''}
            
            ${languages ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Languages</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                <p style="color:#475569;font-size:${s.base}px">${languages.replace(/\n/g, '<br>')}</p>
            </div>` : ''}
            
            ${certs ? `
            <div style="margin-bottom:18px">
                <h3 style="color:${themeColor};font-size:${s.h3}px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px">Certifications</h3>
                <div class="section-divider" style="height:2px;background:linear-gradient(to right,${themeColor},transparent);margin-bottom:10px"></div>
                <p style="color:#475569;font-size:${s.base}px">${certs.replace(/\n/g, '<br>')}</p>
            </div>` : ''}
        </div>
    `;
    
    document.getElementById('resumePreview').style.fontFamily = `'${font}', sans-serif`;
}

function downloadPDF() {
    // Create a hidden form to submit for PDF generation
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("resume.generate") }}';
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="full_name" value="${document.getElementById('fullName').value}">
        <input type="hidden" name="email" value="${document.getElementById('email').value}">
        <input type="hidden" name="theme_color" value="${themeColor}">
        <input type="hidden" name="font_family" value="${document.getElementById('fontSelect').value}">
        <input type="hidden" name="font_size" value="${document.getElementById('fontSize').value}">
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
