export function FormErrors({ errors }: { errors: Record<string, string> }) {
    if (Object.keys(errors).length === 0) {
        return null;
    }

    return (
        <div className="rounded-md border border-destructive p-3 text-sm text-destructive">
            {Object.entries(errors).map(([key, message]) => (
                <p key={key}>{message}</p>
            ))}
        </div>
    );
}
