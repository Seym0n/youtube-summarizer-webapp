<x-base>
    <div class="min-h-screen bg-gradient-to-br from-red-400 to-red-600">
        <!-- Hero Section with Call-to-Action -->
        <section class="relative px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold text-white sm:text-6xl drop-shadow-lg">
                    YouTube Video Summarizer
                </h1>
                <p class="mt-6 text-lg text-white sm:text-xl drop-shadow-md">
                    Get instant summaries of any YouTube video in your preferred format and language.
                </p>
                
                <!-- Beautiful Enhanced Input Form -->
                <div class="mx-auto mt-10 max-w-4xl rounded-3xl bg-white/95 backdrop-blur-sm p-10 shadow-2xl border border-white/20">
                    <form id="summaryForm" class="space-y-8">
                        <!-- YouTube URL Input with Clipboard Icon -->
                        <div class="relative">
                            <label for="youtube-url" class="block text-sm font-semibold text-gray-800 mb-3">YouTube URL</label>
                            <div class="flex rounded-2xl border-2 border-gray-200 bg-gray-50/50 shadow-inner hover:border-red-300 focus-within:border-red-500 transition-colors">
                                <input
                                    type="url"
                                    id="youtube-url"
                                    name="url"
                                    placeholder="https://youtube.com/watch?v=..."
                                    required
                                    class="block w-full flex-1 border-0 bg-transparent py-4 pl-6 pr-4 text-gray-900 placeholder:text-gray-500 focus:ring-0 text-base rounded-l-2xl"
                                >
                                <button
                                    type="button"
                                    id="pasteBtn"
                                    class="flex items-center justify-center px-6 py-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-r-2xl transition-colors"
                                    title="Paste from clipboard"
                                >
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75A1.125 1.125 0 0 1 3.75 20.625V6.375c0-.621.504-1.125 1.125-1.125h3.75M9 11.25h8.25M9 15h8.25M15.75 8.25V4.875c0-.621-.504-1.125-1.125-1.125H8.625c-.621 0-1.125.504-1.125 1.125v3.375" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Grid Layout for Summary Type and Language -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Summary Type Dropdown -->
                            <div class="space-y-3">
                                <label for="summary-type" class="block text-sm font-semibold text-gray-800">Summary Type</label>
                                <div class="relative">
                                    <select
                                        id="summary-type"
                                        name="type"
                                        required
                                        class="block w-full rounded-2xl border-2 border-gray-200 bg-gray-50/50 px-6 py-4 shadow-inner hover:border-red-300 focus:border-red-500 focus:ring-0 text-base appearance-none cursor-pointer transition-colors"
                                    >
                                        <option value="quick">Quick Summary</option>
                                        <option value="bulletpoints">Bullet Points</option>
                                        <option value="structured">Structured Summary</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Language Search Dropdown -->
                            <div class="space-y-3">
                                <label for="language-search" class="block text-sm font-semibold text-gray-800">Language</label>
                                <div class="relative">
                                    <!-- Search Input -->
                                    <input
                                        type="text"
                                        id="language-search"
                                        placeholder="Search language..."
                                        class="block w-full rounded-2xl border-2 border-gray-200 bg-gray-50/50 px-6 py-4 shadow-inner hover:border-red-300 focus:border-red-500 focus:ring-0 text-base transition-colors"
                                        autocomplete="off"
                                    >
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Dropdown List -->
                                    <div id="language-dropdown" class="hidden absolute z-10 mt-2 w-full bg-white border-2 border-gray-200 rounded-2xl shadow-xl max-h-60 overflow-auto">
                                        <div class="py-2">
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="automatic">
                                                Automatic Detection
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="en">
                                                English
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="es">
                                                Spanish
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="fr">
                                                French
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="de">
                                                German
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="zh">
                                                Chinese
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="ja">
                                                Japanese
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="ru">
                                                Russian
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="pt">
                                                Portuguese
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="ar">
                                                Arabic
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="hi">
                                                Hindi
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="it">
                                                Italian
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="ko">
                                                Korean
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="nl">
                                                Dutch
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="tr">
                                                Turkish
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="pl">
                                                Polish
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="sv">
                                                Swedish
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="da">
                                                Danish
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="no">
                                                Norwegian
                                            </div>
                                            <div class="language-option px-6 py-3 hover:bg-red-50 cursor-pointer transition-colors" data-value="fi">
                                                Finnish
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden input to store selected language -->
                                    <input type="hidden" id="language" name="language" value="automatic" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="summarizeBtn"
                            class="w-full rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 disabled:bg-gray-400"
                        >
                            <span id="btnText">Summarize Video</span>
                            <span id="btnSpinner" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Generating Summary...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Summary Display Section -->
        <section id="summarySection" class="hidden px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <div class="rounded-2xl bg-white p-8 shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Video Summary</h2>
                    
                    <!-- Loading State -->
                    <div id="loadingState" class="hidden text-center py-8">
                        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-red-600">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generating your summary...
                        </div>
                    </div>

                    <!-- Summary Content -->
                    <div id="summaryContent" class="prose max-w-none">
                        <!-- Summary will be inserted here -->
                    </div>

                    <!-- Error State -->
                    <div id="errorState" class="hidden">
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p id="errorMessage">Something went wrong. Please try again.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features and Statistics Section -->
        <section class="bg-white py-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Why Choose Our Summarizer?</h2>
                    <p class="mt-4 text-lg text-gray-600">Fast, accurate, and multilingual video summaries</p>
                </div>
                
                <div class="mx-auto mt-16 grid max-w-4xl grid-cols-1 gap-8 sm:grid-cols-3">
                    <div class="text-center">
                        <img src="https://placehold.co/128x128" alt="Fast Processing" class="mx-auto h-32 w-32 rounded-lg">
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Fast Processing</h3>
                        <p class="mt-2 text-gray-600">Get summaries in seconds, not minutes</p>
                    </div>
                    
                    <div class="text-center">
                        <img src="https://placehold.co/128x128" alt="Multiple Formats" class="mx-auto h-32 w-32 rounded-lg">
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Multiple Formats</h3>
                        <p class="mt-2 text-gray-600">Choose from quick, bullet points, or structured summaries</p>
                    </div>
                    
                    <div class="text-center">
                        <img src="https://placehold.co/128x128" alt="Multilingual Support" class="mx-auto h-32 w-32 rounded-lg">
                        <h3 class="mt-6 text-xl font-semibold text-gray-900">Multilingual Support</h3>
                        <p class="mt-2 text-gray-600">Summaries in 19+ languages</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-16 text-center">
                    <div class="rounded-2xl bg-gray-50 p-8">
                        <h3 class="text-2xl font-bold text-gray-900">Videos Summarized</h3>
                        <p class="mt-2 text-4xl font-bold text-red-600" id="videoCount">{{ $totalVideos ?? 0 }}</p>
                        <p class="mt-2 text-gray-600">And counting...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="bg-gray-50 py-16">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Frequently Asked Questions</h2>
                </div>
                
                <div class="mx-auto mt-16 space-y-4">
                    <div class="rounded-2xl bg-white shadow-sm">
                        <button class="faq-trigger flex w-full items-center justify-between p-6 text-left" data-target="faq1">
                            <span class="text-lg font-semibold text-gray-900">How does the YouTube summarizer work?</span>
                            <span class="faq-icon text-red-600 transition-transform">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </button>
                        <div id="faq1" class="faq-content hidden p-6 pt-0">
                            <p class="text-gray-600">Our AI-powered system analyzes the audio and video content of YouTube videos to generate comprehensive summaries in your preferred format and language.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white shadow-sm">
                        <button class="faq-trigger flex w-full items-center justify-between p-6 text-left" data-target="faq2">
                            <span class="text-lg font-semibold text-gray-900">What's the difference between summary types?</span>
                            <span class="faq-icon text-red-600 transition-transform">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </button>
                        <div id="faq2" class="faq-content hidden p-6 pt-0">
                            <p class="text-gray-600">Quick provides a brief overview, Bullet Points offers key takeaways in list format, and Structured gives detailed summaries with headings and sections.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white shadow-sm">
                        <button class="faq-trigger flex w-full items-center justify-between p-6 text-left" data-target="faq3">
                            <span class="text-lg font-semibold text-gray-900">Is there a usage limit?</span>
                            <span class="faq-icon text-red-600 transition-transform">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </button>
                        <div id="faq3" class="faq-content hidden p-6 pt-0">
                            <p class="text-gray-600">Yes, we allow up to 10 video summaries per day per IP address to ensure fair usage for all users.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-xl font-semibold">YouTube Video Summarizer</h3>
                    <p class="mt-2 text-gray-400">Fast, accurate video summaries powered by AI</p>
                    <p class="mt-4 text-sm text-gray-400">
                        Built with <a href="https://tempestphp.com" class="text-red-400 hover:text-red-300">Tempest PHP Framework</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>

</x-base>
