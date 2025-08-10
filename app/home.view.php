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
                
                <!-- Rounded Input Form -->
                <div class="mx-auto mt-10 max-w-2xl rounded-2xl bg-white p-8 shadow-2xl">
                    <form id="summaryForm" class="space-y-6">
                        <!-- YouTube URL Input with Clipboard Icon -->
                        <div class="relative">
                            <label for="youtube-url" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                            <div class="mt-2 flex rounded-full border border-gray-300 bg-white shadow-sm">
                                <input
                                    type="url"
                                    id="youtube-url"
                                    name="url"
                                    placeholder="https://youtube.com/watch?v=..."
                                    required
                                    class="block w-full flex-1 border-0 bg-transparent py-3 pl-4 pr-12 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm rounded-l-full"
                                >
                                <button
                                    type="button"
                                    id="pasteBtn"
                                    class="flex items-center justify-center px-4 py-2 text-gray-400 hover:text-gray-600"
                                    title="Paste from clipboard"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75A1.125 1.125 0 0 1 3.75 20.625V6.375c0-.621.504-1.125 1.125-1.125h3.75M9 11.25h8.25M9 15h8.25M15.75 8.25V4.875c0-.621-.504-1.125-1.125-1.125H8.625c-.621 0-1.125.504-1.125 1.125v3.375" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Summary Type Dropdown -->
                        <div>
                            <label for="summary-type" class="block text-sm font-medium text-gray-700">Summary Type</label>
                            <select
                                id="summary-type"
                                name="type"
                                required
                                class="mt-2 block w-full rounded-full border border-gray-300 bg-white px-4 py-3 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            >
                                <option value="quick">Quick Summary</option>
                                <option value="bulletpoints">Bullet Points</option>
                                <option value="structured">Structured Summary</option>
                            </select>
                        </div>

                        <!-- Language Dropdown -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                            <select
                                id="language"
                                name="language"
                                required
                                class="mt-2 block w-full rounded-full border border-gray-300 bg-white px-4 py-3 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            >
                                <option value="automatic">Automatic Detection</option>
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="zh">Chinese</option>
                                <option value="ja">Japanese</option>
                                <option value="ru">Russian</option>
                                <option value="pt">Portuguese</option>
                                <option value="ar">Arabic</option>
                                <option value="hi">Hindi</option>
                                <option value="it">Italian</option>
                                <option value="ko">Korean</option>
                                <option value="nl">Dutch</option>
                                <option value="tr">Turkish</option>
                                <option value="pl">Polish</option>
                                <option value="sv">Swedish</option>
                                <option value="da">Danish</option>
                                <option value="no">Norwegian</option>
                                <option value="fi">Finnish</option>
                            </select>
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
