<?php 
session_start();
include('doctor/includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Medical Assistant - Symptom & Medicine Guide</title>
    
    <!-- CSS FILES -->        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <link href="css/owl.theme.default.min.css" rel="stylesheet">
    <link href="css/templatemo-medic-care.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
            padding-top: 80px;
        }
        #chatbot-container {
            max-width: 600px;
            margin: 100px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        #chat-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        #chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .medical-info {
            background-color: #e6f3ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .warning-note {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            color: #856404;
            padding: 10px;
            margin: 10px 0;
            font-size: 0.9em;
        }
        #chat-input-area {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }
        #user-input {
            flex-grow: 1;
            padding: 12px 15px;
            border: 2px solid #007bff;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-right: 10px;
            outline: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        #user-input:focus {
            border-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0,123,255,0.2);
        }
        #voice-search-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        #voice-search-btn:hover {
            background-color: #218838;
            transform: scale(1.1);
        }
        #voice-search-btn.listening {
            background-color: #dc3545;
            animation: pulse 1.5s infinite;
        }
        #send-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        #send-btn:hover {
            background-color: #0056b3;
        }
        #text-to-speech-btn {
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 10px;
        }
        #text-to-speech-btn:hover {
            background-color: #138496;
            transform: scale(1.1);
        }
        #text-to-speech-btn.speaking {
            background-color: #ffc107;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <?php include_once('includes/header.php');?>

    <main>
        <div id="chatbot-container">
            <div id="chat-header">
                AI Medical Assistant
            </div>
            <div id="chat-messages"></div>
            <div id="chat-input-area">
                <button id="voice-search-btn" title="Voice Search">
                    <i class="bi bi-mic"></i>
                </button>
                <input type="text" id="user-input" placeholder="Describe your symptoms or health concern...">
                <button id="text-to-speech-btn" title="Read Home Remedies">
                    <i class="bi bi-volume-up"></i>
                </button>
                <button id="send-btn" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </main>

    <?php include_once('includes/footer.php');?>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/scrollspy.min.js"></script>
    <script src="js/custom.js"></script>

    <script>
        // Comprehensive Medical Knowledge Base
        const medicalKnowledge = {
            // Digestive Issues
            digestiveIssues: {
                'fever': {
                    keywords: ['fever', 'temperature', 'hot', 'chills'],
                    medicines: [
                        'Paracetamol (Acetaminophen)',
                        'Ibuprofen',
                        'Aspirin (for adults)',
                        'Naproxen'
                    ],
                    advice: 'Rest, stay hydrated, and monitor temperature. Consult a doctor if fever persists over 3 days or exceeds 103°F (39.4°C).'
                },
                'body pain': {
                    keywords: ['body pain', 'muscle ache', 'pain all over', 'sore body'],
                    medicines: [
                        'Ibuprofen',
                        'Naproxen',
                        'Acetaminophen',
                        'Aspirin'
                    ],
                    advice: 'Apply heat or cold compress, rest, and stay hydrated. Persistent body pain may require medical consultation.'
                },
                'acid reflux': {
                    keywords: ['heartburn', 'acid reflux', 'chest burn', 'stomach acid'],
                    medicines: [
                        'Omeprazole',
                        'Ranitidine',
                        'Antacids (Tums, Rolaids)',
                        'Famotidine'
                    ],
                    advice: 'Avoid spicy and fatty foods, eat smaller meals, and avoid lying down immediately after eating.'
                },
                'bloating and gas': {
                    keywords: ['bloating', 'gas', 'stomach swelling', 'flatulence'],
                    medicines: [
                        'Simethicone',
                        'Activated Charcoal',
                        'Beano',
                        'Probiotics'
                    ],
                    advice: 'Eat slowly, avoid gas-producing foods, and practice stress management.'
                },
                'constipation': {
                    keywords: ['constipation', 'hard stool', 'difficulty passing stool'],
                    medicines: [
                        'Psyllium Husk',
                        'Docusate Sodium',
                        'Milk of Magnesia',
                        'Miralax'
                    ],
                    advice: 'Increase fiber intake, drink plenty of water, and exercise regularly.'
                }
            },
            // Respiratory Issues
            respiratoryIssues: {
                'common cold': {
                    keywords: ['cold', 'runny nose', 'sneezing', 'congestion'],
                    medicines: [
                        'Pseudoephedrine',
                        'Phenylephrine',
                        'Guaifenesin',
                        'Dextromethorphan'
                    ],
                    advice: 'Rest, stay hydrated, use saline nasal spray, and get plenty of sleep.'
                },
                'cough': {
                    keywords: ['cough', 'dry cough', 'wet cough', 'throat irritation'],
                    medicines: [
                        'Dextromethorphan',
                        'Guaifenesin',
                        'Honey-based cough syrup',
                        'Lozenges'
                    ],
                    advice: 'Stay hydrated, use a humidifier, and avoid irritants.'
                }
            },
            // Mental Health
            mentalHealthIssues: {
                'anxiety': {
                    keywords: ['anxiety', 'stress', 'worried', 'nervous'],
                    medicines: [
                        'Sertraline',
                        'Alprazolam',
                        'Lorazepam',
                        'Escitalopram'
                    ],
                    advice: 'Practice mindfulness, consider therapy, maintain a balanced lifestyle.'
                }
            },
            // Neurological Issues
            neurologicalIssues: {
                'headache': {
                    keywords: ['headache', 'head pain', 'head ache', 'migraine', 'head hurting'],
                    medicines: [
                        'Ibuprofen',
                        'Acetaminophen',
                        'Aspirin',
                        'Naproxen',
                        'Paracetamol'
                    ],
                    advice: 'Rest in a quiet, dark room. Stay hydrated, apply a cold or warm compress. If headaches are severe or persistent, consult a doctor.'
                }
            },
            // Cardiovascular Issues
            cardiovascularIssues: {
                'hypertension': {
                    keywords: ['high blood pressure', 'hypertension', 'bp high', 'blood pressure'],
                    medicines: [
                        'Lisinopril',
                        'Amlodipine',
                        'Losartan',
                        'Metoprolol',
                        'Hydrochlorothiazide'
                    ],
                    advice: 'Maintain a healthy diet, reduce salt intake, exercise regularly, manage stress, and take medications as prescribed by a doctor.'
                },
                'low blood pressure': {
                    keywords: ['low blood pressure', 'bp low', 'hypotension'],
                    medicines: [
                        'Fludrocortisone',
                        'Midodrine',
                        'Salt tablets'
                    ],
                    advice: 'Stay hydrated, avoid sudden position changes, wear compression stockings, and consult a healthcare professional.'
                }
            },
            // Metabolic Issues
            metabolicIssues: {
                'high blood sugar': {
                    keywords: ['high sugar', 'diabetes', 'blood sugar high', 'hyperglycemia'],
                    medicines: [
                        'Metformin',
                        'Glipizide',
                        'Insulin',
                        'Januvia',
                        'Ozempic'
                    ],
                    advice: 'Monitor blood sugar levels, follow a balanced diet, exercise regularly, take medications as prescribed, and consult an endocrinologist.'
                },
                'low blood sugar': {
                    keywords: ['low sugar', 'hypoglycemia', 'blood sugar low'],
                    medicines: [
                        'Glucose tablets',
                        'Glucagon emergency kit'
                    ],
                    advice: 'Consume quick-acting carbohydrates, monitor blood sugar, adjust diet and medication with a healthcare professional.'
                }
            },
            // Reproductive Health
            reproductiveHealth: {
                'menstrual cycle issues': {
                    keywords: ['menstrual pain', 'period pain', 'cramps', 'irregular periods', 'menstruation'],
                    medicines: [
                        'Ibuprofen',
                        'Naproxen',
                        'Mefenamic acid',
                        'Birth control pills (for regulation)',
                        'Acetaminophen'
                    ],
                    advice: 'Use heat therapy, stay hydrated, get adequate rest, exercise moderately. Consult a gynecologist for persistent or severe symptoms.'
                }
            }
        };

        const generalAdvice = [
            'This information is for general guidance and not a substitute for professional medical advice.',
            'Always consult a healthcare professional for accurate diagnosis and personalized treatment.',
            'Individual experiences may vary, and symptoms can be complex.'
        ];

        // Extend medical knowledge base with home remedies
        const homeRemedies = {
            'fever': [
                'Rest and stay hydrated',
                'Use a lukewarm compress',
                'Drink herbal tea with ginger and honey',
                'Take plenty of sleep'
            ],
            'body pain': [
                'Apply warm or cold compress',
                'Gentle stretching exercises',
                'Massage with essential oils',
                'Turmeric milk before bed'
            ],
            'headache': [
                'Drink plenty of water',
                'Practice relaxation techniques',
                'Apply peppermint oil on temples',
                'Use cold compress',
                'Try meditation or deep breathing'
            ],
            'acid reflux': [
                'Eat smaller meals',
                'Avoid spicy and acidic foods',
                'Drink chamomile tea',
                'Eat bananas and melons',
                'Chew gum to increase saliva'
            ],
            'menstrual cramps': [
                'Use a heating pad',
                'Drink ginger tea',
                'Practice light yoga',
                'Eat dark chocolate',
                'Stay hydrated'
            ],
            'anxiety': [
                'Practice deep breathing',
                'Try meditation',
                'Drink chamomile tea',
                'Get regular exercise',
                'Maintain a consistent sleep schedule'
            ]
        };

        function findMedicalInformation(query) {
            const lowerQuery = query.toLowerCase();
            
            // Expand search to include home remedies
            for (const condition in homeRemedies) {
                if (lowerQuery.includes(condition)) {
                    let response = `Condition: ${condition.toUpperCase()}\n\n`;
                    
                    response += "Home Remedies:\n";
                    homeRemedies[condition].forEach(remedy => {
                        response += `• ${remedy}\n`;
                    });
                    
                    return response;
                }
            }
            
            // Search through all medical issue categories
            for (const category in medicalKnowledge) {
                for (const condition in medicalKnowledge[category]) {
                    const conditionData = medicalKnowledge[category][condition];
                    
                    // Check if any keyword matches the query
                    const matchedKeywords = conditionData.keywords.filter(
                        keyword => lowerQuery.includes(keyword)
                    );

                    if (matchedKeywords.length > 0) {
                        let response = `Condition: ${condition.toUpperCase()}\n\n`;
                        
                        if (conditionData.medicines && conditionData.medicines.length > 0) {
                            response += "Recommended Medicines:\n";
                            conditionData.medicines.forEach(medicine => {
                                response += `• ${medicine}\n`;
                            });
                        }
                        
                        if (conditionData.advice) {
                            response += `\nAdvice: ${conditionData.advice}\n`;
                        }
                        
                        return response;
                    }
                }
            }
            
            // Fallback response for unrecognized conditions
            return `I'm sorry, I couldn't find specific information about your condition. 
Please consult a healthcare professional for accurate medical advice. 
Some general health tips: 
• Stay hydrated 
• Get adequate rest 
• Maintain a balanced diet 
• Practice good hygiene`;
        }

        function speakResponse(text) {
            if (!text) {
                text = "I'm sorry, I couldn't find any information about that condition.";
            }

            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                
                // Improved female voice selection
                const voices = window.speechSynthesis.getVoices();
                const femaleVoices = voices.filter(voice => 
                    voice.name.toLowerCase().includes('female') ||
                    voice.gender === 'female' ||
                    voice.name.toLowerCase().includes('karen') ||
                    voice.name.toLowerCase().includes('susan') ||
                    voice.name.toLowerCase().includes('emma') ||
                    voice.name.toLowerCase().includes('lily')
                );

                // Prioritize first female voice, fallback to default if no female voice found
                if (femaleVoices.length > 0) {
                    utterance.voice = femaleVoices[0];
                }

                utterance.pitch = 1.5;  // Higher pitch for female voice
                utterance.rate = 0.9;   // Slightly slower rate for clarity
                
                window.speechSynthesis.speak(utterance);
            }
        }

        function speakHomeRemedies(remedies) {
            if (!window.speechSynthesis) return;

            const synth = window.speechSynthesis;
            textToSpeechBtn.classList.add('speaking');
            textToSpeechBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';

            const utterances = remedies.map(remedy => {
                const utterance = new SpeechSynthesisUtterance(remedy);
                utterance.rate = 0.8; // Slower speech for clarity
                return utterance;
            });

            // Speak remedies sequentially
            utterances.forEach((utterance, index) => {
                utterance.onend = () => {
                    if (index === utterances.length - 1) {
                        textToSpeechBtn.classList.remove('speaking');
                        textToSpeechBtn.innerHTML = '<i class="bi bi-volume-up"></i>';
                    }
                };
            });

            // Cancel any ongoing speech
            synth.cancel();
            utterances.forEach(utterance => synth.speak(utterance));
        }

        function sendMessage() {
            const userInput = document.getElementById('user-input');
            const chatMessages = document.getElementById('chat-messages');
            
            if (userInput.value.trim() === '') return;

            // Add user message to chat
            const userMessageDiv = document.createElement('div');
            userMessageDiv.classList.add('user-message');
            userMessageDiv.textContent = userInput.value;
            chatMessages.appendChild(userMessageDiv);

            // Process user input and generate response
            const response = findMedicalInformation(userInput.value);

            // Add bot response to chat
            const botMessageDiv = document.createElement('div');
            botMessageDiv.classList.add('bot-message');
            botMessageDiv.textContent = response;
            chatMessages.appendChild(botMessageDiv);

            // Speak the bot's response with lady voice
            speakResponse(response);

            // Scroll to bottom of chat
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Clear input
            userInput.value = '';
        }

        // Voice Search and Text-to-Speech Functionality
        function initVoiceInteraction() {
            const voiceSearchBtn = document.getElementById('voice-search-btn');
            const textToSpeechBtn = document.getElementById('text-to-speech-btn');
            const userInput = document.getElementById('user-input');

            // Check browser support for speech recognition and synthesis
            if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const recognition = new SpeechRecognition();

                // Speech Recognition Configuration
                recognition.continuous = false;
                recognition.lang = 'en-US';
                recognition.interimResults = false;

                // Speech Synthesis for Home Remedies
                const synth = window.speechSynthesis;

                // Event Listeners for Voice Search
                recognition.onstart = () => {
                    voiceSearchBtn.classList.add('listening');
                    voiceSearchBtn.innerHTML = '<i class="bi bi-mic-fill"></i>';
                };

                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript.trim();
                    userInput.value = transcript;
                    voiceSearchBtn.classList.remove('listening');
                    voiceSearchBtn.innerHTML = '<i class="bi bi-mic"></i>';

                    // Automatically search and find home remedies
                    const medicalInfo = findMedicalInformation(transcript);
                    if (medicalInfo) {
                        // Extract condition from medical information
                        const condition = medicalInfo.split('\n')[0].replace('Condition: ', '').toLowerCase();
                        const remedies = homeRemedies[condition] || [];

                        if (remedies.length > 0) {
                            // Display home remedies
                            const botMessages = document.getElementById('chat-messages');
                            const remediesContainer = document.createElement('div');
                            remediesContainer.classList.add('bot-message');
                            const remediesDiv = document.createElement('div');
                            remediesDiv.classList.add('message', 'medical-info');
                            
                            remediesDiv.innerHTML = `<strong>Home Remedies for ${condition.toUpperCase()}:</strong><br>`;
                            remedies.forEach(remedy => {
                                remediesDiv.innerHTML += `• ${remedy}<br>`;
                            });

                            remediesContainer.appendChild(remediesDiv);
                            botMessages.appendChild(remediesContainer);

                            // Automatically speak home remedies
                            speakHomeRemedies(remedies);
                        }
                    }

                    // Trigger send message
                    sendMessage();
                };

                // Text-to-Speech for Home Remedies
                function speakHomeRemedies(remedies) {
                    if (!synth) return;

                    textToSpeechBtn.classList.add('speaking');
                    textToSpeechBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';

                    const utterances = remedies.map(remedy => {
                        const utterance = new SpeechSynthesisUtterance(remedy);
                        utterance.rate = 0.8; // Slower speech for clarity
                        return utterance;
                    });

                    // Speak remedies sequentially
                    utterances.forEach((utterance, index) => {
                        utterance.onend = () => {
                            if (index === utterances.length - 1) {
                                textToSpeechBtn.classList.remove('speaking');
                                textToSpeechBtn.innerHTML = '<i class="bi bi-volume-up"></i>';
                            }
                        };
                    });

                    // Cancel any ongoing speech
                    synth.cancel();
                    utterances.forEach(utterance => synth.speak(utterance));
                }

                // Toggle Voice Recognition
                voiceSearchBtn.addEventListener('click', () => {
                    if (voiceSearchBtn.classList.contains('listening')) {
                        recognition.stop();
                    } else {
                        recognition.start();
                    }
                });

                // Toggle Text-to-Speech
                textToSpeechBtn.addEventListener('click', () => {
                    if (synth.speaking) {
                        synth.cancel();
                        textToSpeechBtn.classList.remove('speaking');
                        textToSpeechBtn.innerHTML = '<i class="bi bi-volume-up"></i>';
                    }
                });
            } else {
                // Disable voice interaction if not supported
                voiceSearchBtn.style.display = 'none';
                textToSpeechBtn.style.display = 'none';
                
                // Provide user feedback
                const botMessages = document.getElementById('chat-messages');
                const errorMessageContainer = document.createElement('div');
                errorMessageContainer.classList.add('bot-message');
                const errorMessageDiv = document.createElement('div');
                errorMessageDiv.classList.add('message');
                errorMessageDiv.textContent = 'Voice search and text-to-speech are not supported in your browser. Please type your message.';
                errorMessageContainer.appendChild(errorMessageDiv);
                botMessages.appendChild(errorMessageContainer);
            }
        }

        // Modify chat input area HTML
        window.onload = function() {
            const chatInputArea = document.getElementById('chat-input-area');
            chatInputArea.innerHTML = `
                <button id="voice-search-btn" title="Voice Search">
                    <i class="bi bi-mic"></i>
                </button>
                <input type="text" id="user-input" placeholder="Describe your symptoms or health concern...">
                <button id="text-to-speech-btn" title="Read Home Remedies">
                    <i class="bi bi-volume-up"></i>
                </button>
                <button id="send-btn" onclick="sendMessage()">Send</button>
            `;

            // Initialize voice interaction
            initVoiceInteraction();

            // Welcome message
            const chatMessages = document.getElementById('chat-messages');
            const welcomeMessageContainer = document.createElement('div');
            welcomeMessageContainer.classList.add('bot-message');
            const welcomeMessageDiv = document.createElement('div');
            welcomeMessageDiv.classList.add('message');
            welcomeMessageDiv.textContent = 'Welcome to the AI Medical Assistant! Describe your symptoms, and I\'ll help you find appropriate medicines and home remedies.';
            welcomeMessageContainer.appendChild(welcomeMessageDiv);
            chatMessages.appendChild(welcomeMessageContainer);
        }
    </script>
</body>
</html>
