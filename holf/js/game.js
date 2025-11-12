const questions = [
  {
    question: "ما هي أول خطوة يجب اتخاذها عند حدوث عطل كهربائي في القاعة؟",
    answers: [
      "فصل التيار الكهربائي عن المنطقة المتضررة",
      "محاولة إصلاح العطل بنفسك",
      "الانتظار حتى ينتهي العطل تلقائياً",
      "إغلاق الأبواب والنوافذ",
    ],
    correct: 0,
  },
  {
    question: "كم مرة يجب فحص أجهزة الإطفاء في المبنى؟",
    answers: ["مرة كل سنة", "مرة كل 6 أشهر", "مرة كل 3 أشهر", "مرة كل شهر"],
    correct: 1,
  },
  {
    question: "عند اكتشاف تسريب مياه في دورة المياه، ماذا يجب فعله؟",
    answers: [
      "تجاهل الأمر إذا كان التسريب بسيطاً",
      "تقديم طلب صيانة فوري وإغلاق محبس المياه",
      "الانتظار حتى ينتهي الدوام لتقديم الطلب",
      "محاولة الإصلاح باستخدام مواد عشوائية",
    ],
    correct: 1,
  },
  {
    question: "ما هي الأولوية المناسبة لطلب صيانة جهاز طابعة لا يعمل؟",
    answers: ["عاجلة - فوري", "عالية - خلال يومين", "متوسطة - خلال أسبوع", "منخفضة - يمكن الانتظار"],
    correct: 2,
  },
  {
    question: "أي من التالي يعتبر من معدات السلامة الشخصية للفنيين؟",
    answers: ["القفازات والنظارات الواقية", "الهاتف المحمول", "حقيبة الأدوات فقط", "بطاقة الهوية"],
    correct: 0,
  },
  {
    question: "عند حدوث حريق صغير، ما هو الإجراء الصحيح؟",
    answers: [
      "محاولة إطفائه بالماء مباشرة",
      "استخدام طفاية الحريق المناسبة وإبلاغ الأمن",
      "فتح النوافذ لتهوية المكان",
      "الانتظار حتى يأتي شخص آخر",
    ],
    correct: 1,
  },
  {
    question: "لماذا من المهم إرفاق صورة عند تقديم طلب صيانة؟",
    answers: [
      "لجعل الطلب يبدو احترافياً",
      "لتوضيح المشكلة للفني وتسريع عملية الإصلاح",
      "لأنها إلزامية في جميع الطلبات",
      "لزيادة حجم الطلب",
    ],
    correct: 1,
  },
  {
    question: "ما هو الغرض من نظام HOLF لطلبات الصيانة؟",
    answers: [
      "تسجيل حضور الموظفين",
      "تنظيم وتسريع معالجة طلبات الصيانة بشكل احترافي",
      "حجز القاعات الدراسية",
      "إدارة المخزون فقط",
    ],
    correct: 1,
  },
  {
    question: "متى يجب تقديم طلب صيانة عاجلة؟",
    answers: [
      "عند وجود خطر على السلامة أو توقف خدمة حيوية",
      "عند كسر كرسي في القاعة",
      "عند الحاجة لتغيير مصباح عادي",
      "عند طلاء الجدران",
    ],
    correct: 0,
  },
  {
    question: "ما هي فائدة متابعة حالة طلب الصيانة برقم الطلب؟",
    answers: [
      "لا فائدة منها",
      "معرفة مرحلة معالجة الطلب والوقت المتوقع للإنجاز",
      "فقط للديكور في النظام",
      "لزيادة عدد الزيارات للموقع",
    ],
    correct: 1,
  },
]

let currentQuestion = 0
let score = 0
let selectedAnswer = null

function startGame() {
  currentQuestion = 0
  score = 0
  selectedAnswer = null

  document.getElementById("start-screen").style.display = "none"
  document.getElementById("question-screen").style.display = "block"
  document.getElementById("result-screen").style.display = "none"

  updateScore()
  showQuestion()
}

function showQuestion() {
  if (currentQuestion >= questions.length) {
    showResults()
    return
  }

  const question = questions[currentQuestion]
  document.getElementById("question-text").textContent = question.question
  document.getElementById("question-number").textContent = `${currentQuestion + 1}/${questions.length}`

  const answersContainer = document.getElementById("answers-container")
  answersContainer.innerHTML = ""

  question.answers.forEach((answer, index) => {
    const button = document.createElement("button")
    button.className = "answer-btn"
    button.textContent = answer
    button.onclick = () => selectAnswer(index)
    answersContainer.appendChild(button)
  })
}

function selectAnswer(index) {
  if (selectedAnswer !== null) return

  selectedAnswer = index
  const question = questions[currentQuestion]
  const buttons = document.querySelectorAll(".answer-btn")

  buttons[index].classList.add(index === question.correct ? "correct" : "wrong")
  buttons[question.correct].classList.add("correct")

  if (index === question.correct) {
    score += 10
    updateScore()
  }

  buttons.forEach((btn) => (btn.disabled = true))

  setTimeout(() => {
    currentQuestion++
    selectedAnswer = null
    showQuestion()
  }, 2000)
}

function updateScore() {
  document.getElementById("score").textContent = score
}

function showResults() {
  document.getElementById("question-screen").style.display = "none"
  document.getElementById("result-screen").style.display = "block"

  const percentage = (score / (questions.length * 10)) * 100
  const resultIcon = document.getElementById("result-icon")
  const resultTitle = document.getElementById("result-title")
  const resultMessage = document.getElementById("result-message")
  const finalScore = document.getElementById("final-score")

  finalScore.textContent = score

  if (percentage === 100) {
    resultIcon.textContent = "🏆"
    resultTitle.textContent = "ممتاز! درجة كاملة"
    resultMessage.textContent = "أنت خبير حقيقي في الصيانة والسلامة! معلوماتك ممتازة وتفهم أهمية الإجراءات الصحيحة."
  } else if (percentage >= 80) {
    resultIcon.textContent = "🌟"
    resultTitle.textContent = "رائع جداً!"
    resultMessage.textContent = "معلوماتك قوية جداً في مجال الصيانة والسلامة. استمر في التعلم لتحقيق الدرجة الكاملة."
  } else if (percentage >= 60) {
    resultIcon.textContent = "👍"
    resultTitle.textContent = "جيد!"
    resultMessage.textContent = "لديك معرفة جيدة بأساسيات الصيانة. حاول مراجعة بعض المعلومات وأعد المحاولة."
  } else {
    resultIcon.textContent = "📚"
    resultTitle.textContent = "يمكنك التحسين"
    resultMessage.textContent = "راجع معلومات السلامة والصيانة وحاول مرة أخرى. التعلم المستمر هو مفتاح النجاح."
  }
}

function restartGame() {
  startGame()
}