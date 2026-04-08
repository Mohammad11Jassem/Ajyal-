import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';

const toPlainText = (value) => String(value ?? '')
    .replace(/[\u0000-\u001F\u007F]/g, ' ')
    .trim();

function MedicalChatApp() {
    const [question, setQuestion] = useState('');
    const [answer, setAnswer] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');

    const submitQuestion = async (event) => {
        event.preventDefault();

        const trimmedQuestion = question.trim();
        if (!trimmedQuestion || isLoading) {
            return;
        }

        setIsLoading(true);
        setError('');
        setAnswer('');

        try {
            const response = await fetch(import.meta.env.VITE_MEDICAL_AI_API_URL || '/api/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ question: trimmedQuestion }),
            });

            if (!response.ok) {
                throw new Error('Failed to fetch answer');
            }

            const data = await response.json();
            setAnswer(data?.answer ? toPlainText(data.answer) : 'لا أعرف');
        } catch (submitError) {
            console.error(submitError);
            setError('تعذر الاتصال بالخادم. يرجى المحاولة مرة أخرى.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div style={styles.page}>
            <div style={styles.card}>
                <h1 style={styles.title}>المساعد الطبي الذكي</h1>
                <p style={styles.subtitle}>اسأل سؤالك الطبي، وسنحاول إعطاءك إجابة مبنية على البيانات المتاحة.</p>

                <form onSubmit={submitQuestion} style={styles.form}>
                    <label htmlFor="question" style={styles.label}>
                        سؤالك
                    </label>
                    <textarea
                        id="question"
                        value={question}
                        onChange={(event) => setQuestion(event.target.value)}
                        placeholder="اكتب سؤالك هنا..."
                        rows={4}
                        style={styles.textarea}
                        disabled={isLoading}
                    />

                    <button type="submit" disabled={isLoading || !question.trim()} style={styles.button}>
                        {isLoading ? 'جارٍ المعالجة...' : 'إرسال السؤال'}
                    </button>
                </form>

                {isLoading && (
                    <div style={styles.loadingBox} role="status" aria-live="polite">
                        <span style={styles.spinner} />
                        <span>جاري تحليل السؤال وإعداد الإجابة...</span>
                    </div>
                )}

                {error && <div style={styles.error}>{error}</div>}

                {!isLoading && answer && (
                    <div style={styles.answerBox}>
                        <h2 style={styles.answerTitle}>الإجابة</h2>
                        <p style={styles.answerText}>{answer}</p>
                    </div>
                )}
            </div>
        </div>
    );
}

const styles = {
    page: {
        minHeight: '100vh',
        margin: 0,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '24px',
        background: 'linear-gradient(135deg, #e6f6f8 0%, #f6fbff 40%, #e8f4ff 100%)',
        fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
    },
    card: {
        width: '100%',
        maxWidth: '760px',
        background: '#ffffff',
        borderRadius: '20px',
        padding: '28px',
        boxShadow: '0 20px 60px rgba(0, 87, 130, 0.16)',
        border: '1px solid rgba(0, 122, 153, 0.15)',
    },
    title: {
        margin: 0,
        color: '#0d3c61',
        fontSize: '28px',
        fontWeight: 700,
    },
    subtitle: {
        marginTop: '8px',
        marginBottom: '20px',
        color: '#40627f',
        fontSize: '15px',
        lineHeight: 1.6,
    },
    form: {
        display: 'grid',
        gap: '12px',
    },
    label: {
        color: '#205072',
        fontWeight: 600,
        fontSize: '14px',
    },
    textarea: {
        border: '1px solid #b8d6e8',
        borderRadius: '12px',
        padding: '14px',
        fontSize: '15px',
        resize: 'vertical',
        outline: 'none',
    },
    button: {
        border: 0,
        borderRadius: '12px',
        padding: '12px 16px',
        background: 'linear-gradient(90deg, #0084a8, #0d5e99)',
        color: '#fff',
        fontSize: '15px',
        fontWeight: 600,
        cursor: 'pointer',
    },
    loadingBox: {
        marginTop: '18px',
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        color: '#0d5e99',
        fontWeight: 600,
    },
    spinner: {
        width: '16px',
        height: '16px',
        border: '2px solid #9fd6ea',
        borderTopColor: '#0d5e99',
        borderRadius: '50%',
        animation: 'medical-spin 0.8s linear infinite',
    },
    answerBox: {
        marginTop: '22px',
        background: '#f3fbff',
        border: '1px solid #c8e9f5',
        borderRadius: '12px',
        padding: '16px',
    },
    answerTitle: {
        marginTop: 0,
        marginBottom: '10px',
        color: '#0d3c61',
        fontSize: '18px',
    },
    answerText: {
        margin: 0,
        color: '#1e3f5a',
        lineHeight: 1.8,
        whiteSpace: 'pre-wrap',
    },
    error: {
        marginTop: '16px',
        color: '#b8233f',
        fontWeight: 600,
    },
};

const rootElement = document.getElementById('medical-chat-root');
if (rootElement) {
    createRoot(rootElement).render(<MedicalChatApp />);
}
