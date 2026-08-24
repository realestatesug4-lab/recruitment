<?php

namespace App\ViewModels;

class ResourcesViewModel
{
    /**
     * Tab filters for the resources index.
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function tabs(): array
    {
        return [
            ['key' => 'all', 'label' => 'All'],
            ['key' => 'career', 'label' => 'Career guides'],
            ['key' => 'seeker', 'label' => 'Job seeker tips'],
            ['key' => 'employer', 'label' => 'Employer guides'],
            ['key' => 'insights', 'label' => 'Industry insights'],
        ];
    }

    /**
     * The highlighted article at the top of the index.
     *
     * @return array<string, mixed>
     */
    public function featured(): array
    {
        return [
            'slug' => 'cv-guide-uganda-2026',
            'category' => 'career',
            'category_label' => 'Career guides',
            'title' => 'The CraneLinks CV Guide: write a CV Ugandan employers actually read',
            'excerpt' => 'A practical, section-by-section walkthrough — from summary to referees — tuned for ATS filters, low-bandwidth downloads, and the expectations of recruiters in Kampala and beyond.',
            'date' => 'Aug 18, 2026',
            'read_time' => '12 min read',
            'author' => 'CraneLinks Careers Team',
        ];
    }

    /**
     * Quick links rendered beside the featured article.
     *
     * @return array<int, array{slug: string, title: string, read_time: string}>
     */
    public function popular(): array
    {
        return [
            ['slug' => 'interview-prep-fintech-kampala', 'title' => 'Interview prep for Kampala’s fintech hiring wave', 'read_time' => '8 min'],
            ['slug' => 'salary-negotiation-ugx', 'title' => 'Salary negotiation in UGX: scripts that work', 'read_time' => '6 min'],
            ['slug' => 'mobile-money-salary-guide', 'title' => 'Getting paid by mobile money: what to confirm first', 'read_time' => '5 min'],
            ['slug' => 'low-data-job-search', 'title' => 'The low-data job search: save bundle while you apply', 'read_time' => '4 min'],
        ];
    }

    /**
     * The full article index, filtered client-side by tab.
     *
     * @return array<int, array<string, mixed>>
     */
    public function articles(): array
    {
        return [
            [
                'slug' => 'cover-letter-that-gets-opened',
                'category' => 'career',
                'category_label' => 'Career guides',
                'badge_class' => 'badge-green',
                'title' => 'How to write a cover letter that actually gets opened',
                'excerpt' => 'Three paragraphs, one page, zero clichés. The exact structure our recruiters recommend for Ugandan applications.',
                'date' => 'Aug 14, 2026',
                'read_time' => '7 min read',
                'author' => 'Aisha N.',
            ],
            [
                'slug' => 'first-job-after-graduation',
                'category' => 'seeker',
                'category_label' => 'Job seeker tips',
                'badge_class' => 'badge-blue',
                'title' => 'Your first job after graduation: a 30-day action plan',
                'excerpt' => 'From campus to contract — a week-by-week plan for building visibility, referrals, and interview momentum.',
                'date' => 'Aug 11, 2026',
                'read_time' => '9 min read',
                'author' => 'Brian K.',
            ],
            [
                'slug' => 'job-post-that-attracts-talent',
                'category' => 'employer',
                'category_label' => 'Employer guides',
                'badge_class' => 'badge-amber',
                'title' => 'Write job posts that attract real talent, not noise',
                'excerpt' => 'Why vague listings flood you with mismatched CVs — and the six fields that filter for quality applicants.',
                'date' => 'Aug 8, 2026',
                'read_time' => '6 min read',
                'author' => 'CraneLinks for Employers',
            ],
            [
                'slug' => 'uganda-tech-hiring-2026',
                'category' => 'insights',
                'category_label' => 'Industry insights',
                'badge_class' => 'badge-green',
                'title' => 'Uganda tech hiring in 2026: roles, ranges, and remote shifts',
                'excerpt' => 'What the data says about demand for engineers, analysts, and support talent — plus where salaries are heading.',
                'date' => 'Aug 5, 2026',
                'read_time' => '10 min read',
                'author' => 'Research Desk',
            ],
            [
                'slug' => 'ats-friendly-applications',
                'category' => 'seeker',
                'category_label' => 'Job seeker tips',
                'badge_class' => 'badge-blue',
                'title' => 'Beat the ATS: format your application for automated screening',
                'excerpt' => 'How applicant tracking systems rank CVs, and the simple formatting rules that keep yours in the running.',
                'date' => 'Jul 30, 2026',
                'read_time' => '8 min read',
                'author' => 'Aisha N.',
            ],
            [
                'slug' => 'shortlisting-without-bias',
                'category' => 'employer',
                'category_label' => 'Employer guides',
                'badge_class' => 'badge-amber',
                'title' => 'Shortlisting without bias: a scorecard approach',
                'excerpt' => 'A lightweight scoring template that keeps hiring panels consistent across hundreds of applications.',
                'date' => 'Jul 26, 2026',
                'read_time' => '7 min read',
                'author' => 'CraneLinks for Employers',
            ],
            [
                'slug' => 'remote-work-bundle-friendly',
                'category' => 'career',
                'category_label' => 'Career guides',
                'badge_class' => 'badge-green',
                'title' => 'Working remotely in Uganda: a bundle-friendly playbook',
                'excerpt' => 'Tools, routines, and connectivity hacks for staying productive and hireable on limited data.',
                'date' => 'Jul 21, 2026',
                'read_time' => '6 min read',
                'author' => 'Brian K.',
            ],
            [
                'slug' => 'agro-processing-jobs-wave',
                'category' => 'insights',
                'category_label' => 'Industry insights',
                'badge_class' => 'badge-green',
                'title' => 'The quiet boom: agro-processing jobs on the rise',
                'excerpt' => 'Where the new factory-floor and operations roles are opening up — and the skills that get you shortlisted.',
                'date' => 'Jul 15, 2026',
                'read_time' => '5 min read',
                'author' => 'Research Desk',
            ],
            [
                'slug' => 'reference-checks-done-right',
                'category' => 'employer',
                'category_label' => 'Employer guides',
                'badge_class' => 'badge-amber',
                'title' => 'Reference checks done right in under 20 minutes',
                'excerpt' => 'The five questions that surface honest signal — and the red flags worth pausing for.',
                'date' => 'Jul 10, 2026',
                'read_time' => '4 min read',
                'author' => 'CraneLinks for Employers',
            ],
        ];
    }
}
