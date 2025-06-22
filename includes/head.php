<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retroviral Solution | Strengthening ARV Access Across Africa</title>
    <link rel="icon" type="image/png" href="/img/logo-1.png" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',  // Deep blue (brand color)
                        secondary: '#3B82F6',  // Lighter blue
                        accent: '#10B981',  // Green for positive actions
                    },
                    keyframes: {
                        'fade-in': {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        'fade-in-up': {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'fade-in-right': {
                            '0%': { opacity: '0', transform: 'translateX(-10px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        }
                    },
                    animation: {
                        'fade-in': 'fade-in 0.5s ease-out forwards',
                        'fade-in-up': 'fade-in-up 0.5s ease-out forwards',
                        'fade-in-right': 'fade-in-right 0.3s ease-out forwards',
                    }
                }
            }
        }





        
    </script>
    
<style>

    html {
        scroll-behavior: smooth;
      }


    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 10px rgba(255, 255, 255, 0);
        }
        50% {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }
    }
    
    /* Animation classes */
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animation-delay-400 {
        animation-delay: 0.4s;
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .hover-shadow-glow:hover {
        animation: glow 2s ease-in-out infinite;
    }
    
    .animate-pulse {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-ping {
        animation: ping 3s cubic-bezier(0, 0, 0.2, 1) infinite;
    }


     /* Custom animations */
  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
  }
  @keyframes float-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  @keyframes underline-expand {
    0% { width: 0; opacity: 0; }
    100% { width: 100%; opacity: 1; }
  }
  @keyframes progress-grow {
    0% { width: 0; }
    100% { width: 100%; }
  }
  @keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.05); opacity: 0.15; }
  }
  
  /* Animation classes */
  .animate-float {
    animation: float 8s ease-in-out infinite;
  }
  .animate-float-slow {
    animation: float-slow 10s ease-in-out infinite;
  }
  .animate-underline-expand {
    animation: underline-expand 1.5s ease-out forwards;
  }
  .animate-progress-grow {
    animation: progress-grow 1.5s ease-out forwards;
  }
  .animate-pulse-slow {
    animation: pulse-slow 6s ease-in-out infinite;
  }
  .highlight-text {
    background-image: linear-gradient(120deg, #3B82F6 0%, #10B981 100%);
    background-repeat: no-repeat;
    background-size: 100% 40%;
    background-position: 0 90%;
    transition: background-size 0.3s ease;
  }
  .highlight-text:hover {
    background-size: 100% 100%;
  }
  
  /* Count-up animation */
  /*.animate-count-up {
    counter-reset: num var(--num);
  }
  .animate-count-up::after {
    content: counter(num);
  }*/

   /* Custom Animations */
   @keyframes float-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
@keyframes underline-expand {
    0% { transform: scaleX(0); }
    100% { transform: scaleX(1); }
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}
@keyframes bounce-horizontal {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(6px); }
}

/* Animation Classes */
.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}
.animate-underline-expand {
    animation: underline-expand 1s cubic-bezier(0.65, 0, 0.35, 1) forwards;
}
.animate-pulse {
    animation: pulse 2s ease-in-out infinite;
}
.animate-bounce-horizontal {
    animation: bounce-horizontal 3s ease-in-out infinite;
}
.highlight-text {
    background-image: linear-gradient(120deg, #3B82F6 0%, #10B981 100%);
    background-repeat: no-repeat;
    background-size: 100% 40%;
    background-position: 0 90%;
    transition: background-size 0.3s ease;
}
.highlight-text:hover {
    background-size: 100% 100%;
}
</style>
</head>