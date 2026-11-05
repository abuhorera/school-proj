<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolWebsiteController extends Controller
{
    // Website Overview & CMS
    public function index()
    {
        $school = School::first();
        return view('website.index', compact('school'));
    }

    // Website Pages
    public function pages()
    {
        $school = School::first();
        $pages = [
            ['title' => 'Home', 'slug' => '/', 'status' => 'Published', 'last_updated' => '2026-11-01'],
            ['title' => 'About Us & Leadership', 'slug' => '/about-us', 'status' => 'Published', 'last_updated' => '2026-10-28'],
            ['title' => 'Academic Curriculum & CBSE', 'slug' => '/academics', 'status' => 'Published', 'last_updated' => '2026-10-25'],
            ['title' => 'Admissions 2026-27', 'slug' => '/admissions', 'status' => 'Published', 'last_updated' => '2026-11-03'],
            ['title' => 'Campus Facilities & Labs', 'slug' => '/facilities', 'status' => 'Published', 'last_updated' => '2026-10-15'],
            ['title' => 'Mandatory CBSE Disclosure', 'slug' => '/cbse-disclosure', 'status' => 'Published', 'last_updated' => '2026-11-02'],
            ['title' => 'Contact & Location', 'slug' => '/contact', 'status' => 'Published', 'last_updated' => '2026-10-10'],
        ];

        return view('website.pages', compact('school', 'pages'));
    }

    // Hero Banner Slides
    public function slides()
    {
        $school = School::first();
        $slides = [
            ['title' => 'Empowering Minds, Shaping Global Leaders', 'subtitle' => 'Admissions Open for Academic Session 2026-27', 'btn_text' => 'Apply Online', 'status' => 'Active', 'order' => 1],
            ['title' => 'State of the Art Robotics & STEM Labs', 'subtitle' => 'Experiential Learning with Modern Infrastructure', 'btn_text' => 'Explore Campus', 'status' => 'Active', 'order' => 2],
            ['title' => '100% CBSE Board Results with Top Honors', 'subtitle' => 'Excellence in Academics and Co-Curricular Domains', 'btn_text' => 'View Achievements', 'status' => 'Active', 'order' => 3],
        ];

        return view('website.slides', compact('school', 'slides'));
    }

    // Facilities Showcase
    public function facilities()
    {
        $school = School::first();
        $facilities = [
            ['name' => 'Smart Interactive Classrooms', 'category' => 'Academic', 'desc' => 'High-definition touch panels and digital whiteboard integration in all rooms.'],
            ['name' => 'Composite Science & Robotics Lab', 'category' => 'STEM', 'desc' => 'Fully equipped physics, chemistry, biology and robotics prototyping stations.'],
            ['name' => 'Olympic Size Sports Complex', 'category' => 'Sports', 'desc' => 'Indoor badminton court, basketball arena, and professional athletic track.'],
            ['name' => 'Digital Library & Reading Lounge', 'category' => 'Resource', 'desc' => 'Over 15,000 titles and high-speed research terminals.'],
        ];

        return view('website.facilities', compact('school', 'facilities'));
    }

    // Testimonials
    public function testimonials()
    {
        $school = School::first();
        $testimonials = [
            ['parent' => 'Dr. R. K. Sharma', 'child' => 'Aarav Sharma (Class 8)', 'content' => 'The holistic approach and personal attention given by teachers at Yug School is exceptional.', 'rating' => 5],
            ['parent' => 'Mrs. Sunita Verma', 'child' => 'Priya Verma (Class 10)', 'content' => 'Outstanding academic rigor combined with strong emphasis on sports and ethics.', 'rating' => 5],
            ['parent' => 'Mr. Amit Kapoor', 'child' => 'Rohan Kapoor (Class 4)', 'content' => 'The digital parent portal and ERP keeps us informed daily with seamless fee and attendance tracking.', 'rating' => 5],
        ];

        return view('website.testimonials', compact('school', 'testimonials'));
    }

    // Mandatory CBSE Public Disclosure
    public function disclosure()
    {
        $school = School::first();
        $disclosures = [
            ['doc' => 'Affiliation & Status Extension Letter', 'authority' => 'CBSE New Delhi', 'valid_till' => '31-03-2028', 'status' => 'Verified'],
            ['doc' => 'Trust & Society Registration Certificate', 'authority' => 'Registrar of Societies', 'valid_till' => 'Permanent', 'status' => 'Verified'],
            ['doc' => 'No Objection Certificate (NOC)', 'authority' => 'State Education Dept', 'valid_till' => 'Permanent', 'status' => 'Verified'],
            ['doc' => 'Building Safety Certificate', 'authority' => 'Public Works Dept (PWD)', 'valid_till' => '30-06-2027', 'status' => 'Verified'],
            ['doc' => 'Fire Safety Certificate & NOC', 'authority' => 'Fire Department', 'valid_till' => '15-08-2027', 'status' => 'Verified'],
            ['doc' => 'Drinking Water & Sanitary Certificate', 'authority' => 'Municipal Health Authority', 'valid_till' => '31-12-2026', 'status' => 'Verified'],
        ];

        return view('website.disclosure', compact('school', 'disclosures'));
    }
}

