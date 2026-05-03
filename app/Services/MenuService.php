<?php

namespace App\Services;

class MenuItem
{
    public function __construct(
        public string $label,
        public string $icon,
        public string $route,
        public string $section,
        public string $activePattern = '',
    ) {
        if (!$this->activePattern) {
            $this->activePattern = str_replace('.', '.', $route) . '.*';
        }
    }
}

class MenuSection
{
    public string $label;
    public string $id;
    /** @var MenuItem[] */
    public array $items = [];

    public function __construct(string $label, string $id)
    {
        $this->label = $label;
        $this->id = $id;
    }

    public function addItem(MenuItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}

class MenuService
{
    /** @var MenuSection[] */
    private array $sections = [];

    public function __construct()
    {
        $this->registerDefaultSections();
    }

    private function registerDefaultSections(): void
    {
        $this->sections = [
            'people' => new MenuSection('People', 's-people'),
            'academics' => new MenuSection('Academics', 's-academics'),
            'subjects' => new MenuSection('Subjects & Curriculum', 's-subjects'),
            'attendance' => new MenuSection('Attendance', 's-attendance'),
            'finance' => new MenuSection('Finance', 's-finance'),
            'timetable' => new MenuSection('Timetable', 's-timetable'),
            'communication' => new MenuSection('Communication', 's-communication'),
            'hostel' => new MenuSection('Hostel & Transport', 's-hostel'),
            'exams' => new MenuSection('Examinations', 's-exams'),
            'datatransfer' => new MenuSection('Data Transfer', 's-datatransfer'),
            'rbac' => new MenuSection('Administration', 's-rbac'),
            'system' => new MenuSection('System', 's-system'),
        ];
    }

    public function addItem(string $section, MenuItem $item): void
    {
        if (!isset($this->sections[$section])) {
            $this->sections[$section] = new MenuSection(ucfirst($section), 's-' . $section);
        }
        $this->sections[$section]->addItem($item);
    }

    public function addSection(string $key, MenuSection $section): void
    {
        $this->sections[$key] = $section;
    }

    public function getSection(string $key): ?MenuSection
    {
        return $this->sections[$key] ?? null;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function hasSection(string $key): bool
    {
        return isset($this->sections[$key]);
    }
}
