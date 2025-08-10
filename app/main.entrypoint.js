// YouTube Summarizer JavaScript functionality
import './main.entrypoint.css';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initializeClipboardPaste();
    initializeSummaryForm();
    initializeFAQ();
});

// Clipboard paste functionality
function initializeClipboardPaste() {
    const pasteBtn = document.getElementById('pasteBtn');
    const urlInput = document.getElementById('youtube-url');
    
    if (pasteBtn && urlInput) {
        pasteBtn.addEventListener('click', async function() {
            try {
                const text = await navigator.clipboard.readText();
                if (isValidYouTubeUrl(text)) {
                    urlInput.value = text;
                } else {
                    alert('Please copy a valid YouTube URL to your clipboard first.');
                }
            } catch (err) {
                console.error('Failed to read clipboard contents: ', err);
                alert('Unable to access clipboard. Please paste the URL manually.');
            }
        });
    }
}

// Validate YouTube URL
function isValidYouTubeUrl(url) {
    const youtubeRegex = /^https?:\/\/(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[\w-]+/;
    return youtubeRegex.test(url);
}

// Summary form functionality
function initializeSummaryForm() {
    const form = document.getElementById('summaryForm');
    const summarySection = document.getElementById('summarySection');
    const loadingState = document.getElementById('loadingState');
    const summaryContent = document.getElementById('summaryContent');
    const errorState = document.getElementById('errorState');
    const errorMessage = document.getElementById('errorMessage');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const summarizeBtn = document.getElementById('summarizeBtn');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const data = {
                url: formData.get('url'),
                type: formData.get('type'),
                language: formData.get('language')
            };
            
            // Validate URL
            if (!isValidYouTubeUrl(data.url)) {
                showError('Please enter a valid YouTube URL');
                return;
            }
            
            // Show loading state
            showLoading();
            
            try {
                const response = await fetch('/api/summarize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSummary(result.summary, data.type);
                    
                    // Update statistics if available
                    if (result.remaining !== undefined) {
                        console.log(`Remaining requests: ${result.remaining}`);
                    }
                } else {
                    showError(result.error || 'An error occurred while generating the summary');
                }
            } catch (error) {
                console.error('Network error:', error);
                showError('Network error. Please check your connection and try again.');
            }
        });
    }
    
    function showLoading() {
        // Show summary section
        summarySection.classList.remove('hidden');
        
        // Scroll to summary section
        summarySection.scrollIntoView({ behavior: 'smooth' });
        
        // Update button state
        summarizeBtn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
        
        // Show loading state in summary section
        loadingState.classList.remove('hidden');
        summaryContent.innerHTML = '';
        errorState.classList.add('hidden');
    }
    
    function showSummary(summary, summaryType) {
        // Hide loading state
        loadingState.classList.add('hidden');
        
        // Reset button state
        summarizeBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
        
        // Hide error state
        errorState.classList.add('hidden');
        
        // Format and show summary based on type
        const formattedSummary = formatSummary(summary, summaryType);
        summaryContent.innerHTML = formattedSummary;
    }
    
    function showError(message) {
        // Hide loading state
        loadingState.classList.add('hidden');
        
        // Reset button state
        summarizeBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
        
        // Show error state
        errorMessage.textContent = message;
        errorState.classList.remove('hidden');
        
        // Clear summary content
        summaryContent.innerHTML = '';
        
        // Show summary section if not already visible
        summarySection.classList.remove('hidden');
        summarySection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Format summary based on type
function formatSummary(summary, summaryType) {
    switch (summaryType) {
        case 'structured':
            return parseMarkdown(summary);
        case 'bulletpoints':
            return parseBulletPoints(summary);
        case 'quick':
        default:
            return `<p class="text-gray-800 leading-relaxed">${escapeHtml(summary)}</p>`;
    }
}

// Simple markdown parser for structured summaries
function parseMarkdown(text) {
    let html = escapeHtml(text);
    
    // Headers
    html = html.replace(/^### (.*$)/gim, '<h3 class="text-xl font-semibold text-gray-900 mt-6 mb-3">$1</h3>');
    html = html.replace(/^## (.*$)/gim, '<h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">$1</h2>');
    html = html.replace(/^# (.*$)/gim, '<h1 class="text-3xl font-bold text-gray-900 mt-8 mb-4">$1</h1>');
    
    // Bold and italic
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold">$1</strong>');
    html = html.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');
    
    // Paragraphs
    html = html.replace(/\n\n/g, '</p><p class="text-gray-800 leading-relaxed mb-4">');
    html = '<p class="text-gray-800 leading-relaxed mb-4">' + html + '</p>';
    
    return html;
}

// Parse bullet points
function parseBulletPoints(text) {
    const lines = text.split('\n');
    let html = '<ul class="list-disc pl-6 space-y-2">';
    
    lines.forEach(line => {
        const trimmed = line.trim();
        if (trimmed.startsWith('*') || trimmed.startsWith('-')) {
            const content = trimmed.substring(1).trim();
            html += `<li class="text-gray-800">${escapeHtml(content)}</li>`;
        } else if (trimmed.length > 0) {
            // Non-bullet line, treat as regular paragraph
            html += `</ul><p class="text-gray-800 leading-relaxed my-4">${escapeHtml(trimmed)}</p><ul class="list-disc pl-6 space-y-2">`;
        }
    });
    
    html += '</ul>';
    
    // Clean up empty ul tags
    html = html.replace(/<ul class="list-disc pl-6 space-y-2"><\/ul>/g, '');
    
    return html;
}

// Escape HTML to prevent XSS
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// FAQ functionality
function initializeFAQ() {
    const faqTriggers = document.querySelectorAll('.faq-trigger');
    
    faqTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon svg');
            
            if (content && icon) {
                if (content.classList.contains('hidden')) {
                    // Show content
                    content.classList.remove('hidden');
                    // Rotate icon (minus sign)
                    icon.style.transform = 'rotate(45deg)';
                } else {
                    // Hide content
                    content.classList.add('hidden');
                    // Reset icon (plus sign)
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        });
    });
}