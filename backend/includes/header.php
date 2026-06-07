<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Coursera</title>
    
    <!-- Custom Theme Variables -->
    <link rel="stylesheet" href="../frontend/modern-theme.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                        ring: 'hsl(var(--ring))',
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: {
                            DEFAULT: 'hsl(var(--primary))',
                            foreground: 'hsl(var(--primary-foreground))'
                        },
                        secondary: {
                            DEFAULT: 'hsl(var(--secondary))',
                            foreground: 'hsl(var(--secondary-foreground))'
                        },
                        destructive: {
                            DEFAULT: 'hsl(var(--destructive))',
                            foreground: 'hsl(var(--destructive-foreground))'
                        },
                        muted: {
                            DEFAULT: 'hsl(var(--muted))',
                            foreground: 'hsl(var(--muted-foreground))'
                        },
                        accent: {
                            DEFAULT: 'hsl(var(--accent))',
                            foreground: 'hsl(var(--accent-foreground))'
                        },
                        popover: {
                            DEFAULT: 'hsl(var(--popover))',
                            foreground: 'hsl(var(--popover-foreground))'
                        },
                        card: {
                            DEFAULT: 'hsl(var(--card))',
                            foreground: 'hsl(var(--card-foreground))'
                        },
                    },
                    borderRadius: {
                        lg: 'var(--radius)',
                        md: 'calc(var(--radius) - 2px)',
                        sm: 'calc(var(--radius) - 4px)'
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground min-h-screen flex flex-col">
    <!-- Header -->
    <header className="sticky top-0 z-50 bg-card border-b border-border shadow-sm">
      <div class="container mx-auto">
        <div class="flex items-center justify-between h-16 px-4">
          <!-- Logo -->
          <a href="index.php" class="flex items-center gap-2">
            <i data-lucide="book-open" class="h-8 w-8 text-primary"></i>
            <span class="text-xl font-bold text-foreground hidden sm:inline">Learnera</span>
          </a>

          <!-- Desktop Navigation -->
          <nav class="hidden lg:flex items-center gap-6">
             <div class="relative group">
                <button class="flex items-center gap-1 text-sm font-medium hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 rounded-md transition-colors">
                  Explore <i data-lucide="chevron-down" class="h-4 w-4 ml-1"></i>
                </button>
                <!-- Simple Dropdown Content (Hover) -->
                <div class="absolute left-0 top-full mt-2 w-56 rounded-md border border-border bg-popover p-1 text-popover-foreground shadow-md opacity-0 group-show:opacity-100 invisible group-hover:visible transition-all z-50">
                    <a href="dashboard.php" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">
                      All Courses
                    </a>
                    <!-- Mock categories -->
                    <a href="#" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">Data Science</a>
                    <a href="#" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">Computer Science</a>
                </div>
             </div>

              <a href="dashboard.php" class="text-sm font-medium transition-colors hover:text-primary text-foreground">Courses</a>
              <a href="leaderboard.php" class="text-sm font-medium transition-colors hover:text-primary text-foreground">Leaderboard</a>
              <a href="progress.php" class="text-sm font-medium transition-colors hover:text-primary text-foreground">Progress</a>
              <a href="#" class="text-sm font-medium transition-colors hover:text-primary text-muted-foreground">Degrees</a>
              <a href="#" class="text-sm font-medium transition-colors hover:text-primary text-muted-foreground">Certificates</a>
              <a href="#" class="text-sm font-medium transition-colors hover:text-primary text-muted-foreground">For Enterprise</a>
          </nav>

          <!-- Search Bar - Desktop -->
          <div class="hidden md:flex items-center flex-1 max-w-md mx-6">
            <div class="relative w-full">
              <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground"></i>
              <input
                type="text"
                placeholder="What do you want to learn?"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10"
              />
            </div>
          </div>

          <!-- Right Side Actions -->
          <div class="flex items-center gap-2">
            <!-- Mobile Search Toggle (visual only for now) -->
            <button class="md:hidden inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
               <i data-lucide="search" class="h-5 w-5"></i>
            </button>

            <!-- Notifications -->
            <button class="hidden sm:inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
               <i data-lucide="bell" class="h-5 w-5"></i>
            </button>

            <!-- User Menu/Auth -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- User Profile Dropdown Placeholder -->
                 <div class="relative group">
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
                        <i data-lucide="user" class="h-5 w-5"></i>
                    </button>
                     <div class="absolute right-0 top-full mt-2 w-48 rounded-md border border-border bg-popover p-1 text-popover-foreground shadow-md invisible group-hover:visible transition-all z-50">
                        <div class="px-2 py-1.5 text-sm font-semibold">
                            Signed in as <br/>
                            <span class="font-normal truncate block"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></span>
                        </div>
                        <div class="h-px bg-muted my-1"></div>
                        <a href="dashboard.php" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">My Learning</a>
                        <a href="leaderboard.php" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">Leaderboard</a>
                        <div class="h-px bg-muted my-1"></div>
                        <a href="auth/logout.php" class="block w-full rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground text-destructive">Log out</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="hidden sm:flex items-center gap-2">
                  <a href="auth/login.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Log In
                  </a>
                  <a href="auth/register.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                    Join for Free
                  </a>
                </div>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="lg:hidden inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
               <i data-lucide="menu" class="h-5 w-5"></i>
            </button>
          </div>
        </div>
      </div>
    </header>
    <main class="flex-1">
