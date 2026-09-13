# Production Readiness Checklist

Use this checklist before deploying the school system to production.

## Runtime

- [ ] Server uses PHP 8.4 or newer, matching `composer.json` and `vendor/composer/platform_check.php`.
- [ ] Composer dependencies installed cleanly with no platform or autoload errors.
- [ ] `APP_KEY` is set and `.env` production values are correct.
- [ ] Database connection is pointing to the production database only.
- [ ] Queue worker is configured if queued jobs are enabled.
- [ ] `storage:link` has been run so uploaded files are accessible.

## Data Integrity

- [ ] Seed data for `tahun_ajaran`, `kelas`, `guru`, `siswa`, `mata_pelajaran`, and `jenis_penilaian` is complete.
- [ ] Active year (`status_aktif = true`) is correct.
- [ ] Jadwal entries match teacher, class, mapel, and year relationships.
- [ ] Absensi rows always reference a valid `jadwal_id` when available.
- [ ] Nilai entries are entered with the correct kelas, mapel, jenis penilaian, and tahun ajaran.

## Business Rules

- [ ] Absensi updates recalculate `nilai_akhir` automatically.
- [ ] Nilai input updates recalculate `nilai_akhir` automatically.
- [ ] Bobot final score matches the intended formula: absensi 10%, tugas 20%, UTS 30%, UAS 40%.
- [ ] Attendance status rules are confirmed, especially whether `terlambat` counts as hadir.
- [ ] Passing grade and predikat ranges are aligned with school policy.

## Access Control

- [ ] Admin, guru, dan siswa can only open pages permitted by their role.
- [ ] Guru can only input nilai for mapel that belong to their jadwal.
- [ ] Master data routes are restricted to admin/TU.

## Deployment

- [ ] `php artisan migrate --force` has been tested in a staging environment.
- [ ] `php artisan test` passes on a machine running PHP 8.4+.
- [ ] Frontend assets are built successfully with `npm run build`.
- [ ] Backup and rollback plan exists before the first production deploy.

## Post-Deploy

- [ ] Login works for admin, guru, and siswa accounts.
- [ ] Absensi input works for at least one class and one schedule.
- [ ] Nilai input updates `nilai_akhir` correctly after save.
- [ ] Rekap absensi and rekap nilai pages load with real data.
- [ ] Export PDF / Excel features work in the production environment.
